<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SecureSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Regenerate session ID periodically for security
        if (Auth::check()) {
            $lastRegeneration = Session::get('last_regeneration', 0);
            $regenerationInterval = config('security.session.regeneration_interval', 900); // 15 minutes default
            
            if (time() - $lastRegeneration > $regenerationInterval) {
                Session::regenerate();
                Session::put('last_regeneration', time());
            }

            // Check for session timeout for sensitive operations
            $lastActivity = Session::get('last_activity', time());
            $timeoutDuration = config('security.password.confirmation_timeout', 3600);
            
            if (time() - $lastActivity > $timeoutDuration) {
                // Mark session as requiring password confirmation for sensitive operations
                Session::put('auth.password_confirmed_at', null);
            }
            
            Session::put('last_activity', time());
        }

        // Add security headers
        $response = $next($request);
        
        if ($response instanceof \Illuminate\Http\Response || $response instanceof \Illuminate\Http\RedirectResponse) {
            $headers = config('security.headers');
            
            $response->headers->set('X-Frame-Options', $headers['x_frame_options']);
            $response->headers->set('X-Content-Type-Options', $headers['x_content_type_options']);
            $response->headers->set('X-XSS-Protection', $headers['x_xss_protection']);
            $response->headers->set('Referrer-Policy', $headers['referrer_policy']);
            
            if ($request->secure()) {
                $hstsValue = 'max-age=' . $headers['hsts_max_age'];
                if ($headers['hsts_include_subdomains']) {
                    $hstsValue .= '; includeSubDomains';
                }
                $response->headers->set('Strict-Transport-Security', $hstsValue);
            }
        }

        return $response;
    }
}