<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Http\Request;

class LoggingService
{
    /**
     * Log an audit action.
     */
    public static function logAudit(
        ?User $user,
        string $action,
        string $model,
        ?int $modelId = null,
        ?array $changes = null,
        ?Request $request = null,
        string $status = 'success'
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'changes' => $changes ? json_encode($changes) : null,
            'ip_address' => self::getClientIp($request),
            'user_agent' => $request?->header('User-Agent'),
            'status' => $status,
        ]);
    }

    /**
     * Log a security event.
     */
    public static function logSecurityEvent(
        string $eventType,
        string $severity = 'info',
        ?User $user = null,
        ?Request $request = null,
        ?string $endpoint = null,
        ?int $responseCode = null,
        ?array $details = null
    ): SecurityLog {
        return SecurityLog::create([
            'user_id' => $user?->id,
            'event_type' => $eventType,
            'severity' => $severity,
            'ip_address' => self::getClientIp($request),
            'user_agent' => $request?->header('User-Agent'),
            'endpoint' => $endpoint ?? $request?->getPathInfo(),
            'method' => $request?->getMethod() ?? 'UNKNOWN',
            'response_code' => $responseCode,
            'details' => $details ? json_encode($details) : null,
            'occurred_at' => now(),
        ]);
    }

    /**
     * Get client IP address, handling proxies.
     */
    public static function getClientIp(?Request $request = null): string
    {
        if (!$request) {
            $request = request();
        }

        // Check for IP from shared internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // Check for IP passed from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        }
        // Check for remote address
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = $request->ip() ?? 'UNKNOWN';
        }

        // Validate and sanitize IP
        $ip = trim($ip);
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = 'INVALID';
        }

        return substr($ip, 0, 45); // Truncate to DB field size
    }

    /**
     * Log failed login attempt.
     */
    public static function logFailedLogin(string $email, ?Request $request = null): void
    {
        self::logSecurityEvent(
            'failed_login',
            'warning',
            null,
            $request,
            '/login',
            401,
            ['email' => $email]
        );
    }

    /**
     * Log successful login.
     */
    public static function logSuccessfulLogin(User $user, ?Request $request = null): void
    {
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => self::getClientIp($request),
        ]);

        self::logSecurityEvent(
            'successful_login',
            'info',
            $user,
            $request,
            '/login',
            200,
            ['user_id' => $user->id, 'role' => $user->role]
        );
    }

    /**
     * Log logout.
     */
    public static function logLogout(User $user, ?Request $request = null): void
    {
        self::logSecurityEvent(
            'logout',
            'info',
            $user,
            $request,
            '/logout',
            200,
            ['user_id' => $user->id]
        );
    }

    /**
     * Log unauthorized access attempt.
     */
    public static function logUnauthorizedAccess(
        string $action,
        ?User $user = null,
        ?Request $request = null,
        ?string $reason = null
    ): void {
        self::logSecurityEvent(
            'unauthorized_access',
            'critical',
            $user,
            $request,
            $request?->getPathInfo(),
            403,
            [
                'action' => $action,
                'reason' => $reason,
                'user_id' => $user?->id,
            ]
        );
    }

    /**
     * Log suspicious activity.
     */
    public static function logSuspiciousActivity(
        string $description,
        ?User $user = null,
        ?Request $request = null,
        ?array $details = null
    ): void {
        self::logSecurityEvent(
            'suspicious_activity',
            'warning',
            $user,
            $request,
            $request?->getPathInfo(),
            null,
            array_merge(['description' => $description], $details ?? [])
        );
    }
}
