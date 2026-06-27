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
use PragmaRX\Google2FA\Google2FA;

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
            $cookieName = 'remember_mfa_' . $user->id;
            if ($request->hasCookie($cookieName)) {
                // Bypass MFA if browser is remembered
                session()->forget(['captcha_question', 'captcha_answer']);
                Auth::login($user, $request->boolean('remember'));
                LoggingService::logSuccessfulLogin($user, $request);
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'))->with('success', 'Logged in successfully!');
            }

            // Store user ID in session temporarily for MFA verification
            session([
                'mfa_user_id' => $user->id,
                'mfa_remember' => $request->boolean('remember'),
            ]);

            LoggingService::logSecurityEvent(
                'mfa_challenge_required',
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
     * Verify the MFA code using TOTP.
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

        if (!$user || !$user->mfa_secret) {
            LoggingService::logSecurityEvent(
                'failed_mfa_attempt',
                'warning',
                $user,
                $request,
                '/login/mfa',
                422,
                ['reason' => 'User not found or MFA not enabled']
            );

            throw ValidationException::withMessages([
                'code' => 'Invalid verification code.',
            ]);
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->mfa_secret);
        $valid = $google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
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
                'code' => 'Invalid verification code.',
            ]);
        }

        // Clear captcha session
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

        // Check if remember browser is checked
        if ($request->boolean('remember_browser')) {
            Cookie::queue('remember_mfa_' . $user->id, 'verified', 60 * 24 * 30); // 30 days
        }

        // Clear MFA session details
        session()->forget(['mfa_user_id', 'mfa_remember']);

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
            'mfa_enabled' => false,
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

    /**
     * Toggle MFA for the authenticated user.
     */
    public function toggleMfa(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // If enabling MFA, redirect to setup page
        if (!$user->mfa_enabled) {
            return redirect()->route('profile.mfa.setup');
        }

        // If disabling MFA, just disable it
        $user->mfa_enabled = false;
        $user->mfa_secret = null;
        $user->save();

        return back()->with('success', "Multi-Factor Authentication has been successfully deactivated!");
    }

    /**
     * Show MFA setup page with QR code.
     */
    public function setupMfa(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->mfa_enabled) {
            return redirect()->route('profile.show')->with('info', 'MFA is already enabled.');
        }

        $google2fa = new Google2FA();
        
        // Generate a new secret key
        $secret = $google2fa->generateSecretKey();
        
        // Store the secret temporarily in session for verification
        session(['mfa_setup_secret' => $secret]);
        
        // Generate QR code URL
        $companyName = config('app.name', 'SecureLearn');
        $email = $user->email;
        $qrCodeUrl = $google2fa->getQRCodeUrl($companyName, $email, $secret);
        
        // Use Google Charts API for QR code generation (simple and reliable)
        $qrCodeImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrCodeUrl);

        return view('profile.mfa-setup', [
            'qrCodeImageUrl' => $qrCodeImageUrl,
            'secret' => $secret,
        ]);
    }

    /**
     * Confirm and enable MFA after verification.
     */
    public function confirmMfa(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => 'Verification code is required.',
            'code.size' => 'Verification code must be exactly 6 digits.',
        ]);

        $secret = session('mfa_setup_secret');
        
        if (!$secret) {
            return redirect()->route('profile.show')->with('error', 'MFA setup session expired. Please try again.');
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        // Enable MFA for the user
        $user->mfa_enabled = true;
        $user->mfa_secret = encrypt($secret);
        $user->save();

        // Clear the temporary secret from session
        session()->forget('mfa_setup_secret');

        LoggingService::logSecurityEvent(
            'mfa_enabled',
            'info',
            $user,
            $request,
            '/profile/mfa/setup',
            200,
            ['email' => $user->email]
        );

        return redirect()->route('profile.show')->with('success', 'Multi-Factor Authentication has been successfully enabled!');
    }

    /**
     * Show the user profile page.
     */
    public function showProfile(Request $request)
    {
        return view('profile.show', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user profile settings.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
