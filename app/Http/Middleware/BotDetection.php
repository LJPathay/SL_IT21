<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BotDetection
{
    /**
     * Known bot user agents patterns
     */
    protected $botPatterns = [
        '/bot/i',
        '/crawler/i',
        '/spider/i',
        '/scraper/i',
        '/curl/i',
        '/wget/i',
        '/python/i',
        '/java/i',
        '/perl/i',
        '/ruby/i',
        '/go-http/i',
        '/headless/i',
        '/phantom/i',
        '/selenium/i',
        '/puppeteer/i',
        '/playwright/i',
        '/chromedriver/i',
        '/geckodriver/i',
    ];

    /**
     * Suspicious headers that indicate bots
     */
    protected $suspiciousHeaders = [
        'x-forwarded-for',
        'x-real-ip',
        'via',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip bot detection for trusted routes
        if ($this->shouldSkipDetection($request)) {
            return $next($request);
        }

        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Check if IP is already blocked
        if ($this->isIpBlocked($ip)) {
            Log::warning('Blocked IP attempted access', ['ip' => $ip, 'user_agent' => $userAgent]);
            return $this->blockedResponse();
        }

        // Check for bot patterns in user agent
        if ($this->isBotUserAgent($userAgent)) {
            $this->incrementSuspiciousCounter($ip);
            Log::warning('Bot detected via user agent', ['ip' => $ip, 'user_agent' => $userAgent]);
            
            if ($this->shouldBlockIp($ip)) {
                $this->blockIp($ip);
                return $this->blockedResponse();
            }
        }

        // Check for missing essential headers (common in simple bots)
        if ($this->hasMissingHeaders($request)) {
            $this->incrementSuspiciousCounter($ip);
            Log::warning('Missing essential headers detected', ['ip' => $ip, 'headers' => $request->headers->all()]);
        }

        // Check for too fast requests (rate limiting)
        if ($this->isRequestingTooFast($ip)) {
            $this->incrementSuspiciousCounter($ip);
            Log::warning('Too many requests detected', ['ip' => $ip]);
            
            if ($this->shouldBlockIp($ip)) {
                $this->blockIp($ip);
                return $this->blockedResponse();
            }
        }

        // Check for headless browser indicators
        if ($this->isHeadlessBrowser($userAgent)) {
            $this->incrementSuspiciousCounter($ip);
            Log::warning('Headless browser detected', ['ip' => $ip, 'user_agent' => $userAgent]);
        }

        return $next($request);
    }

    /**
     * Check if bot detection should be skipped for this route
     */
    protected function shouldSkipDetection(Request $request): bool
    {
        $skipRoutes = [
            'health',
            'api/*',
            'up',
        ];

        foreach ($skipRoutes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user agent matches bot patterns
     */
    protected function isBotUserAgent(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true; // Empty user agent is suspicious
        }

        foreach ($this->botPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for missing essential headers
     */
    protected function hasMissingHeaders(Request $request): bool
    {
        // Check for common browser headers that should be present
        $essentialHeaders = ['accept', 'accept-language'];
        
        foreach ($essentialHeaders as $header) {
            if (!$request->hasHeader($header)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP is requesting too fast
     */
    protected function isRequestingTooFast(string $ip): bool
    {
        $key = 'request_rate:' . $ip;
        $requests = Cache::get($key, 0);
        
        if ($requests > 30) { // More than 30 requests in 1 minute
            return true;
        }

        Cache::put($key, $requests + 1, 60); // Increment counter, 1 minute expiry
        return false;
    }

    /**
     * Check for headless browser indicators
     */
    protected function isHeadlessBrowser(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return false;
        }

        $headlessIndicators = [
            'HeadlessChrome',
            'HeadlessFirefox',
            'Selenium',
            'PhantomJS',
            'WebDriver',
        ];

        foreach ($headlessIndicators as $indicator) {
            if (strpos($userAgent, $indicator) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Increment suspicious activity counter for IP
     */
    protected function incrementSuspiciousCounter(string $ip): void
    {
        $key = 'suspicious:' . $ip;
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, 3600); // 1 hour expiry
    }

    /**
     * Check if IP should be blocked based on suspicious activity
     */
    protected function shouldBlockIp(string $ip): bool
    {
        $key = 'suspicious:' . $ip;
        $count = Cache::get($key, 0);
        return $count >= 5; // Block after 5 suspicious activities
    }

    /**
     * Block IP address
     */
    protected function blockIp(string $ip): void
    {
        $key = 'blocked:' . $ip;
        Cache::put($key, true, 7200); // Block for 2 hours
        Log::alert('IP blocked due to suspicious activity', ['ip' => $ip]);
    }

    /**
     * Check if IP is blocked
     */
    protected function isIpBlocked(string $ip): bool
    {
        return Cache::has('blocked:' . $ip);
    }

    /**
     * Return blocked response
     */
    protected function blockedResponse(): Response
    {
        return response()->json([
            'message' => 'Access denied. Your IP has been temporarily blocked due to suspicious activity.',
        ], 403);
    }
}
