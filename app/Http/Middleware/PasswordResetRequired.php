<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetRequired
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->needs_password_reset) {
            // Allow access to profile routes, logout, and password updates to prevent redirect loops
            if (!$request->is('profile*') && !$request->is('logout') && !$request->is('profile/update*')) {
                return redirect()->route('profile.show')
                    ->with('warning', 'A security administrator has forced a password reset on your account. Please update your password to proceed.');
            }
        }

        return $next($request);
    }
}
