<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SecurityMonitoringService
{
    /**
     * Log a security event.
     */
    public function logSecurityEvent(string $event, array $data = []): void
    {
        $logData = array_merge([
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
        ], $data);

        Log::channel('security')->warning($event, $logData);

        // Store in database for admin review
        if (auth()->check()) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $event,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'new_values' => $data,
                'performed_at' => now(),
            ]);
        }
    }

    /**
     * Check for suspicious activity patterns.
     */
    public function checkSuspiciousActivity(Request $request): bool
    {
        $ip = $request->ip();
        $userId = auth()->id();
        
        // Check for rapid requests from same IP
        if ($this->hasRapidRequests($ip)) {
            $this->logSecurityEvent('rapid_requests_detected', ['ip' => $ip]);
            return true;
        }

        // Check for multiple failed login attempts
        if ($this->hasMultipleFailedLogins($ip)) {
            $this->logSecurityEvent('multiple_failed_logins', ['ip' => $ip]);
            return true;
        }

        // Check for suspicious user agent patterns
        if ($this->hasSuspiciousUserAgent($request->userAgent())) {
            $this->logSecurityEvent('suspicious_user_agent', [
                'user_agent' => $request->userAgent(),
                'ip' => $ip,
            ]);
            return true;
        }

        // Check for IP address changes for logged-in users
        if ($userId && $this->hasIpAddressChange($userId, $ip)) {
            $this->logSecurityEvent('ip_address_change', [
                'user_id' => $userId,
                'old_ip' => $this->getLastKnownIp($userId),
                'new_ip' => $ip,
            ]);
        }

        return false;
    }

    /**
     * Check for rapid requests from the same IP.
     */
    protected function hasRapidRequests(string $ip): bool
    {
        $key = "rapid_requests:{$ip}";
        $requests = Cache::get($key, 0);
        
        if ($requests > 100) { // More than 100 requests per minute
            return true;
        }
        
        Cache::put($key, $requests + 1, 60); // Track for 1 minute
        return false;
    }

    /**
     * Check for multiple failed login attempts.
     */
    protected function hasMultipleFailedLogins(string $ip): bool
    {
        $key = "failed_logins:{$ip}";
        $attempts = Cache::get($key, 0);
        
        return $attempts >= 10; // 10 or more failed attempts
    }

    /**
     * Check for suspicious user agent patterns.
     */
    protected function hasSuspiciousUserAgent(?string $userAgent): bool
    {
        if (!$userAgent) {
            return true; // No user agent is suspicious
        }

        $suspiciousPatterns = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i',
            '/curl/i',
            '/wget/i',
            '/python/i',
            '/php/i',
            '/java/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for IP address changes for a user.
     */
    protected function hasIpAddressChange(int $userId, string $currentIp): bool
    {
        $lastIp = $this->getLastKnownIp($userId);
        
        if ($lastIp && $lastIp !== $currentIp) {
            $this->updateLastKnownIp($userId, $currentIp);
            return true;
        }
        
        if (!$lastIp) {
            $this->updateLastKnownIp($userId, $currentIp);
        }
        
        return false;
    }

    /**
     * Get the last known IP address for a user.
     */
    protected function getLastKnownIp(int $userId): ?string
    {
        return Cache::get("user_last_ip:{$userId}");
    }

    /**
     * Update the last known IP address for a user.
     */
    protected function updateLastKnownIp(int $userId, string $ip): void
    {
        Cache::put("user_last_ip:{$userId}", $ip, 24 * 60 * 60); // Store for 24 hours
    }

    /**
     * Record a failed login attempt.
     */
    public function recordFailedLogin(string $email, string $ip): void
    {
        $key = "failed_logins:{$ip}";
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, 15 * 60); // Store for 15 minutes

        $this->logSecurityEvent('failed_login_attempt', [
            'email' => $email,
            'ip' => $ip,
            'attempts' => $attempts,
        ]);
    }

    /**
     * Clear failed login attempts for an IP.
     */
    public function clearFailedLogins(string $ip): void
    {
        Cache::forget("failed_logins:{$ip}");
    }

    /**
     * Check if an IP is temporarily blocked.
     */
    public function isIpBlocked(string $ip): bool
    {
        return Cache::has("blocked_ip:{$ip}");
    }

    /**
     * Temporarily block an IP address.
     */
    public function blockIp(string $ip, int $minutes = 30): void
    {
        Cache::put("blocked_ip:{$ip}", true, $minutes * 60);
        
        $this->logSecurityEvent('ip_blocked', [
            'ip' => $ip,
            'duration_minutes' => $minutes,
        ]);
    }

    /**
     * Get security statistics for admin dashboard.
     */
    public function getSecurityStats(): array
    {
        $today = now()->startOfDay();
        
        return [
            'failed_logins_today' => AdminActivityLog::where('action', 'failed_login_attempt')
                ->where('performed_at', '>=', $today)
                ->count(),
            'blocked_ips_count' => $this->getBlockedIpsCount(),
            'suspicious_activities_today' => AdminActivityLog::whereIn('action', [
                'rapid_requests_detected',
                'suspicious_user_agent',
                'multiple_failed_logins',
            ])->where('performed_at', '>=', $today)->count(),
            'admin_actions_today' => AdminActivityLog::where('performed_at', '>=', $today)
                ->whereHas('user', function ($query) {
                    $query->where('role', 'admin');
                })->count(),
        ];
    }

    /**
     * Get count of currently blocked IPs.
     */
    protected function getBlockedIpsCount(): int
    {
        // This is a simplified count - in production you might want to store this differently
        return 0; // Placeholder since we're using cache
    }
}