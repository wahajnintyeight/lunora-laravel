<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\SecurityMonitoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        protected SecurityMonitoringService $securityService
    ) {}

    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Check for suspicious activity
        if ($this->securityService->checkSuspiciousActivity($request)) {
            // Continue with normal flow but log the activity
        }

        try {
            $request->authenticate();

            // Clear failed login attempts on successful login
            $this->securityService->clearFailedLogins($request->ip());

            // Log successful login
            $this->securityService->logSecurityEvent('successful_login', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
            ]);

            $request->session()->regenerate();

            // Update last login timestamp
            Auth::user()->update(['last_login_at' => now()]);

            // Redirect admins to admin dashboard, others to home
            $user = Auth::user();

            if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Welcome back, admin!');
            }

            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Record failed login attempt
            $this->securityService->recordFailedLogin(
                $request->input('email'),
                $request->ip()
            );

            throw $e;
        }
    }

    /**
     * Handle user logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log logout event
        $this->securityService->logSecurityEvent('user_logout', [
            'user_id' => auth()->id(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }
}
