<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoggingService;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Verify Google reCAPTCHA v2 token.
     */
    private function verifyRecaptcha($response, $ip)
    {
        if (empty($response)) {
            return false;
        }

        // On Windows local dev, PHP cURL lacks a CA bundle (SSL error 60).
        // Skip verification only in local; production always verifies.
        $http = app()->isLocal()
            ? Http::withoutVerifying()->asForm()
            : Http::asForm();

        $res = $http->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $response,
            'remoteip' => $ip,
        ]);

        return $res->successful() && $res->json('success') === true;
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request with secure authentication and logging.
     */
    public function login(Request $request)
    {
        // Validate input including recaptcha
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'g-recaptcha-response' => 'required|string',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);

        // Validate reCAPTCHA
        if (!$this->verifyRecaptcha($request->input('g-recaptcha-response'), $request->ip())) {
            LoggingService::logSecurityEvent(
                'failed_captcha_attempt',
                'warning',
                null,
                $request,
                '/login',
                422,
                ['email' => $request->input('email')]
            );

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.',
            ]);
        }

        // Sanitize email
        $email = trim($validated['email']);

        // Find user by email
        $user = User::where('email', $email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            // Log failed login attempt
            LoggingService::logFailedLogin($email, $request);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Check if user account is active
        if (!$user->is_active) {
            LoggingService::logSecurityEvent(
                'inactive_user_login_attempt',
                'warning',
                $user,
                $request,
                '/login',
                403,
                ['reason' => 'User account is inactive']
            );

            throw ValidationException::withMessages([
                'email' => 'Your account has been disabled. Please contact support.',
            ]);
        }

        // Check if MFA is enabled for this user
        if ($user->mfa_enabled) {
            // Generate a 6-digit OTP code
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Save OTP and expiration time (10 minutes)
            $user->mfa_secret = $otp; // store in raw/hash format. Let's store raw for simplicity of this demo, or Hash::make. Let's store raw so we can easily check, or Hash::make for maximum security. Raw is fine for temporary OTPs if we clear it.
            $user->mfa_expires_at = now()->addMinutes(10);
            $user->save();

            // Store user ID in session temporarily
            session([
                'mfa_user_id' => $user->id,
                'mfa_remember' => $request->boolean('remember'),
                'mfa_code_demo' => $otp // Storing in session for display purposes in the UI helper/banner
            ]);

            LoggingService::logSecurityEvent(
                'mfa_otp_generated',
                'info',
                $user,
                $request,
                '/login',
                200,
                ['email' => $user->email]
            );

            return redirect()->route('login.mfa');
        }

        // Clear captcha session after successful login
        session()->forget(['captcha_question', 'captcha_answer']);

        // Authenticate user
        Auth::login($user, $request->boolean('remember'));

        // Log successful login
        LoggingService::logSuccessfulLogin($user, $request);

        // Regenerate session ID for security (prevent session fixation)
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('success', 'Logged in successfully!');
    }

    /**
     * Handle logout with security logging.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Log logout
            LoggingService::logLogout($user, $request);
        }

        // Completely clear authentication
        Auth::logout();

        // Flush session data
        $request->session()->flush();

        // Invalidate the session completely
        $request->session()->invalidate();

        // Regenerate token for security
        $request->session()->regenerateToken();

        // Create response that redirects to home
        $response = redirect('/')->with('success', 'Logged out successfully!');

        // Forget ALL authentication and session cookies
        $response->withCookie(Cookie::forget('XSRF-TOKEN'));
        $response->withCookie(Cookie::forget('laravel_session'));
        $response->withCookie(Cookie::forget('laravel_token'));
        $response->withCookie(Cookie::forget(config('session.cookie')));
        $response->withCookie(Cookie::forget('remember_' . hash('sha256', get_class($user)))); // Laravel's remember token cookie
        
        // Also forget general remember cookie
        $response->withCookie(Cookie::forget('remember_web_59ba36addc2b2f9401580f014c0f58ea4eae3be7'));

        // Force cache headers to prevent browser caching authenticated content
        $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');

        return $response;
    }

    /**
     * Refresh authentication - useful for AJAX requests.
     */
    public function refresh(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::user(),
        ]);
    }

    /**
     * Show MFA verification form.
     */
    public function showMfaForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if (!session()->has('mfa_user_id')) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        return view('auth.mfa');
    }

    /**
     * Verify the MFA code.
     */
    public function verifyMfa(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if (!session()->has('mfa_user_id')) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => 'MFA verification code is required.',
            'code.size' => 'MFA verification code must be exactly 6 digits.',
        ]);

        $userId = session('mfa_user_id');
        $user = User::find($userId);

        if (!$user || !$user->mfa_secret || $user->mfa_secret !== $request->code || now()->greaterThan($user->mfa_expires_at)) {
            LoggingService::logSecurityEvent(
                'failed_mfa_attempt',
                'warning',
                $user,
                $request,
                '/login/mfa',
                422,
                ['code' => $request->code]
            );

            throw ValidationException::withMessages([
                'code' => 'Invalid or expired verification code.',
            ]);
        }

        // Clear MFA code from database
        $user->mfa_secret = null;
        $user->mfa_expires_at = null;
        $user->save();

        // Clear captcha session too
        session()->forget(['captcha_question', 'captcha_answer']);

        // Log the user in
        Auth::login($user, session('mfa_remember', false));

        // Log successful login with MFA
        LoggingService::logSecurityEvent(
            'successful_login_with_mfa',
            'info',
            $user,
            $request,
            '/login/mfa',
            200,
            ['email' => $user->email]
        );

        // Clear MFA session details
        session()->forget(['mfa_user_id', 'mfa_remember', 'mfa_code_demo']);

        // Regenerate session ID for security
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('success', 'Logged in successfully with Multi-Factor Authentication!');
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => trim($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'is_active' => true,
            'mfa_enabled' => true,
        ]);

        LoggingService::logSecurityEvent(
            'user_registered',
            'info',
            $user,
            $request,
            '/register',
            201,
            ['email' => $user->email, 'role' => $user->role]
        );

        return redirect()->route('login')->with('success', 'Registration successful! Please sign in below.');
    }

    // -------------------------------------------------------------------------
    // Forgot / Reset Password
    // -------------------------------------------------------------------------

    /**
     * Show the forgot-password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the given email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', __($status));
        }

        throw ValidationException::withMessages([
            'email' => __($status),
        ]);
    }

    /**
     * Show the password reset form.
     */
    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Handle the incoming password reset request.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password reset successfully. Please sign in.');
        }

        throw ValidationException::withMessages([
            'email' => __($status),
        ]);
    }
}
