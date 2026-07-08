<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class ApiThrottle
{
    /**
     * The rate limiter instance.
     */
    protected $limiter;

    /**
     * Create a new middleware instance.
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return sha1($user->id . '|' . $request->ip());
        }

        return sha1($request->ip() . '|' . $request->route()->getName());
    }

    /**
     * Create a response for rate limit exceeded.
     */
    protected function buildResponse(string $key, int $maxAttempts): Response
    {
        $seconds = $this->limiter->availableIn($key);

        return response()->json([
            'message' => 'Too many requests. Please try again later.',
            'retry_after' => $seconds,
            'limit' => $maxAttempts,
        ], 429);
    }
}
