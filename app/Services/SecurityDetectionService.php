<?php

namespace App\Services;

use App\Models\SecurityDetection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SecurityDetectionService
{
    /**
     * Detect phishing characteristics in email content
     */
    public function detectPhishing(string $emailContent, string $senderEmail, string $subject, ?string $sourceId = null): ?SecurityDetection
    {
        $suspiciousPatterns = [
            '/urgent/i',
            '/immediate action required/i',
            '/verify your account/i',
            '/click here/i',
            '/password reset/i',
            '/suspended account/i',
            '/confirm your identity/i',
            '/wire transfer/i',
            '/bank details/i',
            '/credit card/i',
        ];

        $suspiciousUrls = [
            '/bit\.ly/i',
            '/tinyurl/i',
            '/goo\.gl/i',
            '/paypal\.com.*login/i',
            '/apple\.com.*signin/i',
            '/microsoft\.com.*verify/i',
        ];

        $riskScore = 0;
        $detectedIssues = [];

        // Check for suspicious patterns
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $emailContent) || preg_match($pattern, $subject)) {
                $riskScore += 2;
                $detectedIssues[] = "Suspicious pattern detected: " . substr($pattern, 1, -2);
            }
        }

        // Check for suspicious URLs
        foreach ($suspiciousUrls as $pattern) {
            if (preg_match($pattern, $emailContent)) {
                $riskScore += 3;
                $detectedIssues[] = "Suspicious URL pattern detected";
            }
        }

        // Check sender domain reputation (simplified)
        $freeEmailDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
        $senderDomain = substr(strrchr($senderEmail, "@"), 1);
        if (!in_array($senderDomain, $freeEmailDomains) && !filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
            $riskScore += 2;
            $detectedIssues[] = "Unusual sender domain";
        }

        // Determine severity
        $severity = 'low';
        if ($riskScore >= 8) {
            $severity = 'critical';
        } elseif ($riskScore >= 5) {
            $severity = 'high';
        } elseif ($riskScore >= 3) {
            $severity = 'medium';
        }

        if ($riskScore >= 2) {
            return SecurityDetection::create([
                'detection_type' => SecurityDetection::TYPE_PHISHING,
                'severity' => $severity,
                'title' => 'Phishing Email Detected',
                'description' => 'Email contains characteristics commonly associated with phishing attacks.',
                'details' => implode(', ', $detectedIssues) . " (Risk Score: $riskScore)",
                'source' => $sourceId ? 'message' : 'email',
                'source_id' => $sourceId ?? $senderEmail,
                'mitigation_steps' => '1. Do not click any links in the email. 2. Verify sender identity through official channels. 3. Report to security team. 4. Delete email if confirmed phishing.',
            ]);
        }

        return null;
    }

    /**
     * Detect social engineering tactics
     */
    public function detectSocialEngineering(string $message, string $context, ?string $sourceId = null): ?SecurityDetection
    {
        $manipulationTactics = [
            '/urgency/i' => 'Creating false urgency',
            '/authority/i' => 'Impersonating authority figures',
            '/scarcity/i' => 'Creating false scarcity',
            '/consensus/i' => 'Using social proof manipulation',
            '/liking/i' => 'Building false rapport',
            '/reciprocity/i' => 'Using obligation tactics',
            '/secret/i' => 'Exploiting secrecy',
            '/exclusive/i' => 'Creating false exclusivity',
            '/only you/i' => 'Targeted manipulation',
            '/trust me/i' => 'Requesting blind trust',
        ];

        $riskScore = 0;
        $detectedTactics = [];

        foreach ($manipulationTactics as $pattern => $tactic) {
            if (preg_match($pattern, $message)) {
                $riskScore += 2;
                $detectedTactics[] = $tactic;
            }
        }

        // Check for pressure tactics
        if (preg_match('/immediately|right now|today only|limited time/i', $message)) {
            $riskScore += 3;
            $detectedTactics[] = 'Time pressure tactics';
        }

        if ($riskScore >= 3) {
            $severity = $riskScore >= 6 ? 'high' : 'medium';

            return SecurityDetection::create([
                'detection_type' => SecurityDetection::TYPE_SOCIAL_ENGINEERING,
                'severity' => $severity,
                'title' => 'Social Engineering Attempt Detected',
                'description' => 'Communication contains manipulation tactics commonly used in social engineering attacks.',
                'details' => implode(', ', $detectedTactics) . " (Risk Score: $riskScore)",
                'source' => 'message',
                'source_id' => $sourceId ?? $context,
                'mitigation_steps' => '1. Verify identity through official channels. 2. Do not share sensitive information. 3. Document the interaction. 4. Report to security team.',
            ]);
        }

        return null;
    }

    /**
     * Assess password security
     */
    public function assessPasswordSecurity(string $password, ?User $user = null): ?SecurityDetection
    {
        $issues = [];
        $riskScore = 0;

        // Check length
        if (strlen($password) < 8) {
            $riskScore += 3;
            $issues[] = 'Password too short (minimum 8 characters)';
        }

        // Check for common patterns
        $commonPasswords = ['password', '123456', 'qwerty', 'admin', 'welcome', 'letmein'];
        foreach ($commonPasswords as $common) {
            if (stripos($password, $common) !== false) {
                $riskScore += 5;
                $issues[] = 'Contains common password pattern';
                break;
            }
        }

        // Check for complexity
        if (!preg_match('/[A-Z]/', $password)) {
            $riskScore += 2;
            $issues[] = 'Missing uppercase letters';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $riskScore += 2;
            $issues[] = 'Missing lowercase letters';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $riskScore += 2;
            $issues[] = 'Missing numbers';
        }
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $riskScore += 2;
            $issues[] = 'Missing special characters';
        }

        // Check for personal information
        if ($user) {
            if (stripos($password, $user->name) !== false) {
                $riskScore += 4;
                $issues[] = 'Contains user name';
            }
            if (stripos($password, $user->email) !== false) {
                $riskScore += 4;
                $issues[] = 'Contains email address';
            }
        }

        if ($riskScore >= 4) {
            $severity = $riskScore >= 10 ? 'critical' : ($riskScore >= 7 ? 'high' : 'medium');

            return SecurityDetection::create([
                'detection_type' => SecurityDetection::TYPE_PASSWORD,
                'severity' => $severity,
                'title' => 'Weak Password Detected',
                'description' => 'Password does not meet security requirements and may be vulnerable to attacks.',
                'details' => implode(', ', $issues) . " (Risk Score: $riskScore)",
                'source' => 'user',
                'source_id' => $user ? $user->id : null,
                'mitigation_steps' => '1. Use minimum 12 characters. 2. Include uppercase, lowercase, numbers, and special characters. 3. Avoid personal information. 4. Use a password manager. 5. Enable 2FA.',
            ]);
        }

        return null;
    }

    /**
     * Detect malware threats in file uploads
     */
    public function detectMalware(string $fileName, string $fileContent, int $fileSize): ?SecurityDetection
    {
        $issues = [];
        $riskScore = 0;

        // Check file extension
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'scr', 'pif', 'com', 'vbs', 'js', 'jar'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (in_array($extension, $dangerousExtensions)) {
            $riskScore += 5;
            $issues[] = 'Dangerous file extension detected';
        }

        // Check file size (unusually large files may contain malware)
        if ($fileSize > 50 * 1024 * 1024) { // 50MB
            $riskScore += 2;
            $issues[] = 'Unusually large file size';
        }

        // Check for suspicious patterns in content (simplified)
        $malwarePatterns = [
            '/eval\s*\(/i',
            '/base64_decode/i',
            '/shell_exec/i',
            '/system\s*\(/i',
            '/passthru\s*\(/i',
            '/exec\s*\(/i',
        ];

        foreach ($malwarePatterns as $pattern) {
            if (preg_match($pattern, $fileContent)) {
                $riskScore += 4;
                $issues[] = 'Suspicious code pattern detected';
                break;
            }
        }

        // Check for double extensions
        if (preg_match('/\.[a-z]{3,4}\.[a-z]{3,4}$/i', $fileName)) {
            $riskScore += 3;
            $issues[] = 'Double file extension detected';
        }

        if ($riskScore >= 3) {
            $severity = $riskScore >= 8 ? 'critical' : ($riskScore >= 5 ? 'high' : 'medium');

            return SecurityDetection::create([
                'detection_type' => SecurityDetection::TYPE_MALWARE,
                'severity' => $severity,
                'title' => 'Potential Malware Detected',
                'description' => 'File upload contains characteristics associated with malware or suspicious content.',
                'details' => implode(', ', $issues) . " (Risk Score: $riskScore)",
                'source' => 'file',
                'source_id' => $fileName,
                'mitigation_steps' => '1. Quarantine the file immediately. 2. Scan with antivirus software. 3. Do not execute the file. 4. Report to security team. 5. Delete if confirmed malicious.',
            ]);
        }

        return null;
    }

    /**
     * Detect unsafe online activities
     */
    public function detectUnsafeOnlineActivity(string $url, string $userAgent, string $ipAddress): ?SecurityDetection
    {
        $issues = [];
        $riskScore = 0;

        // Check for suspicious URL patterns
        $suspiciousPatterns = [
            '/\.onion/i' => 'Tor network access',
            '/bit\.ly|tinyurl|goo\.gl/i' => 'URL shortener (potential phishing)',
            '/free.*download|crack|keygen/i' => 'Software piracy site',
            '/casino|gambling|betting/i' => 'Gambling site',
            '/torrent|pirate/i' => 'Torrent/piracy site',
        ];

        foreach ($suspiciousPatterns as $pattern => $issue) {
            if (preg_match($pattern, $url)) {
                $riskScore += 2;
                $issues[] = $issue;
            }
        }

        // Check for non-HTTPS (simplified)
        if (!preg_match('/^https:\/\//i', $url)) {
            $riskScore += 1;
            $issues[] = 'Non-HTTPS connection';
        }

        // Check for suspicious user agents
        $suspiciousUserAgents = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i',
        ];

        foreach ($suspiciousUserAgents as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $riskScore += 2;
                $issues[] = 'Suspicious user agent';
                break;
            }
        }

        // Check for multiple rapid requests (would need tracking, simplified here)
        // This would typically be implemented with rate limiting

        if ($riskScore >= 2) {
            $severity = $riskScore >= 6 ? 'high' : 'medium';

            return SecurityDetection::create([
                'detection_type' => SecurityDetection::TYPE_ONLINE_ACTIVITY,
                'severity' => $severity,
                'title' => 'Unsafe Online Activity Detected',
                'description' => 'Online activity contains patterns associated with risky or malicious behavior.',
                'details' => implode(', ', $issues) . " (Risk Score: $riskScore)",
                'source' => 'network',
                'source_id' => $ipAddress,
                'mitigation_steps' => '1. Verify the legitimacy of the website. 2. Ensure HTTPS connection. 3. Use VPN for sensitive activities. 4. Report suspicious activity to security team.',
            ]);
        }

        return null;
    }

    /**
     * Resolve a security detection
     */
    public function resolveDetection(SecurityDetection $detection, string $resolutionNotes): void
    {
        $detection->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
            'mitigation_steps' => $resolutionNotes,
        ]);
    }

    /**
     * Get security statistics
     */
    public function getSecurityStats(): array
    {
        return [
            'total_detections' => SecurityDetection::count(),
            'unresolved_detections' => SecurityDetection::unresolved()->count(),
            'phishing_count' => SecurityDetection::byType(SecurityDetection::TYPE_PHISHING)->count(),
            'social_engineering_count' => SecurityDetection::byType(SecurityDetection::TYPE_SOCIAL_ENGINEERING)->count(),
            'password_count' => SecurityDetection::byType(SecurityDetection::TYPE_PASSWORD)->count(),
            'malware_count' => SecurityDetection::byType(SecurityDetection::TYPE_MALWARE)->count(),
            'online_activity_count' => SecurityDetection::byType(SecurityDetection::TYPE_ONLINE_ACTIVITY)->count(),
            'critical_count' => SecurityDetection::bySeverity(SecurityDetection::SEVERITY_CRITICAL)->count(),
            'high_count' => SecurityDetection::bySeverity(SecurityDetection::SEVERITY_HIGH)->count(),
        ];
    }
}
