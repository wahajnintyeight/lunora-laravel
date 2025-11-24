<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordConfirmation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $confirmedAt = Session::get('auth.password_confirmed_at');
        $timeout = config('security.password.confirmation_timeout', 3600);

        // Check if password confirmation is required
        if (!$confirmedAt || (time() - $confirmedAt) > $timeout) {
            // Store the intended URL
            Session::put('url.intended', $request->fullUrl());
            
            return redirect()->route('password.confirm')
                ->with('error', 'Please confirm your password to continue.');
        }

        return $next($request);
    }
}