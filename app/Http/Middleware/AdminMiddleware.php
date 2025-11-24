<?php

namespace App\Http\Middleware;

use App\Models\AdminActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access the admin panel.');
        }

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        // Check if user account is active
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated. Please contact support.');
        }

        // Check for suspicious activity (multiple failed attempts, unusual IP changes)
        $this->checkSuspiciousActivity($request);

        // Log admin activity for audit trail
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch') || $request->isMethod('delete')) {
            AdminActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $request->method() . ' ' . $request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'changes' => $request->except(['password', '_token', '_method']),
                'performed_at' => now(),
            ]);
        }

        return $next($request);
    }

    /**
     * Check for suspicious admin activity
     */
    private function checkSuspiciousActivity(Request $request): void
    {
        $user = Auth::user();
        $currentIp = $request->ip();
        $sessionKey = 'admin_last_ip_' . $user->id;
        $lastIp = session($sessionKey);

        // If IP changed, log it for security monitoring
        if ($lastIp && $lastIp !== $currentIp) {
            AdminActivityLog::create([
                'user_id' => $user->id,
                'action' => 'IP_CHANGE',
                'ip_address' => $currentIp,
                'user_agent' => $request->userAgent(),
                'changes' => [
                    'previous_ip' => $lastIp,
                    'new_ip' => $currentIp,
                    'timestamp' => now()->toISOString()
                ],
                'performed_at' => now(),
            ]);
        }

        // Store current IP for next request
        session([$sessionKey => $currentIp]);
    }
}
