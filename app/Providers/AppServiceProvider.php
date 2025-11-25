<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use App\Listeners\EmailSendingListener;
use App\Listeners\EmailSentListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configureEmailEvents();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        $rateLimits = config('security.rate_limiting');

        // Login rate limiting
        RateLimiter::for('login', function (Request $request) use ($rateLimits) {
            return Limit::perMinute($rateLimits['login']['attempts'])
                ->by($request->email . '|' . $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->back()
                        ->withErrors(['email' => 'Too many login attempts. Please try again later.'])
                        ->withHeaders($headers);
                });
        });

        // Password reset rate limiting
        RateLimiter::for('password-reset', function (Request $request) use ($rateLimits) {
            return Limit::perMinute($rateLimits['password_reset']['attempts'])
                ->by($request->email . '|' . $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->back()
                        ->withErrors(['email' => 'Too many password reset attempts. Please try again later.'])
                        ->withHeaders($headers);
                });
        });

        // Email verification rate limiting
        RateLimiter::for('email-verification', function (Request $request) use ($rateLimits) {
            return Limit::perMinute($rateLimits['email_verification']['attempts'])
                ->by($request->user()?->id ?: $request->ip());
        });

        // Admin actions rate limiting (more permissive for admin users)
        RateLimiter::for('admin-actions', function (Request $request) use ($rateLimits) {
            return Limit::perMinute($rateLimits['admin_actions']['attempts'])
                ->by($request->user()?->id ?: $request->ip());
        });

        // General API rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Configure email event listeners.
     */
    protected function configureEmailEvents(): void
    {
        Event::listen(MessageSending::class, EmailSendingListener::class);
        Event::listen(MessageSent::class, EmailSentListener::class);
    }
}
