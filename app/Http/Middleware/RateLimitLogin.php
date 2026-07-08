<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class RateLimitLogin
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
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, 5)) {
            return $this->buildResponse($key);
        }

        $this->limiter->hit($key, 60); // 5 attempts per minute

        return $next($request);
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->ip() . '|' . $request->input('email') . '|' . $request->route()->getName()
        );
    }

    /**
     * Create a response for rate limit exceeded.
     */
    protected function buildResponse(string $key): Response
    {
        $seconds = $this->limiter->availableIn($key);

        return response()->json([
            'message' => Lang::get('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
        ], 429);
    }
}
