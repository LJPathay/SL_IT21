<?php

namespace App\Http\Middleware;

use App\Services\LoggingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogSecurityEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // List of routes that should be logged for security
        $shouldLog = $this->shouldLog($request);

        if ($shouldLog) {
            $user = Auth::user();

            // Log the request
            LoggingService::logSecurityEvent(
                'http_request',
                'info',
                $user,
                $request,
                $request->getPathInfo(),
                null,
                [
                    'method' => $request->getMethod(),
                    'url' => $request->url(),
                    'parameters' => $this->sanitizeParameters($request->all()),
                ]
            );
        }

        $response = $next($request);

        // Log response code for security events
        if ($shouldLog) {
            LoggingService::logSecurityEvent(
                'http_response',
                $this->getSeverity($response->getStatusCode()),
                Auth::user(),
                $request,
                $request->getPathInfo(),
                $response->getStatusCode()
            );
        }

        return $response;
    }

    /**
     * Determine if the request should be logged.
     */
    private function shouldLog(Request $request): bool
    {
        // Don't log CSS, JS, images, etc.
        $ignoredExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2', 'ttf'];
        $path = $request->getPathInfo();

        foreach ($ignoredExtensions as $ext) {
            if (str_ends_with($path, '.' . $ext)) {
                return false;
            }
        }

        // Log authentication and sensitive routes
        return true;
    }

    /**
     * Sanitize parameters to remove sensitive data.
     */
    private function sanitizeParameters(array $parameters): array
    {
        $sensitive = ['password', 'password_confirmation', 'token', 'secret', 'api_key', 'credit_card'];

        foreach ($sensitive as $key) {
            if (isset($parameters[$key])) {
                $parameters[$key] = '***REDACTED***';
            }
        }

        return $parameters;
    }

    /**
     * Determine severity based on HTTP status code.
     */
    private function getSeverity(int $statusCode): string
    {
        if ($statusCode >= 500) {
            return 'critical';
        } elseif ($statusCode >= 400) {
            return 'warning';
        }
        return 'info';
    }
}
