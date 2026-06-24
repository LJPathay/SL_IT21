<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoggingService;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
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
        // Validate input
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

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

        // Clear previous sessions for security
        // $user->sessions()->delete();

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
}
