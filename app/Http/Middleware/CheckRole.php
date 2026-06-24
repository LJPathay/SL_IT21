<?php

namespace App\Http\Middleware;

use App\Services\LoggingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Check if user has one of the required roles
        if (!$user->hasAnyRole($roles)) {
            // Log unauthorized access (don't include details in abort message)
            LoggingService::logUnauthorizedAccess(
                'role_check_failed',
                $user,
                $request,
                'Required roles: ' . implode(', ', $roles) . ', User role: ' . $user->role
            );

            // Generic 403 response without revealing page existence
            abort(403);
        }

        return $next($request);
    }
}
