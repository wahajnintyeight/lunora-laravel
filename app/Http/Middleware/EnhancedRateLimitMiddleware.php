<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class EnhancedRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $limiterName = 'global'): Response
    {
        $key = $this->resolveRequestSignature($request, $limiterName);
        $config = $this->getRateLimitConfig($limiterName);

        if (RateLimiter::tooManyAttempts($key, $config['attempts'])) {
            $seconds = RateLimiter::availableIn($key);
            
            // Log rate limit violations
            \Log::warning('Rate limit exceeded', [
                'limiter' => $limiterName,
                'ip' => $request->ip(),
                'user_id' => auth()->id(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'retry_after' => $seconds,
            ]);

            return $this->buildResponse($seconds, $config['message'] ?? 'Too many requests.');
        }

        RateLimiter::hit($key, $config['decay_minutes'] * 60);

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $config['attempts']);
        $response->headers->set('X-RateLimit-Remaining', max(0, $config['attempts'] - RateLimiter::attempts($key)));

        return $response;
    }

    /**
     * Resolve the request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request, string $limiterName): string
    {
        $user = $request->user();
        
        if ($user) {
            return "rate-limit:{$limiterName}:user:{$user->id}";
        }
        
        return "rate-limit:{$limiterName}:ip:{$request->ip()}";
    }

    /**
     * Get rate limit configuration for the specified limiter.
     */
    protected function getRateLimitConfig(string $limiterName): array
    {
        $configs = [
            'global' => [
                'attempts' => 60,
                'decay_minutes' => 1,
                'message' => 'Too many requests. Please slow down.',
            ],
            'login' => [
                'attempts' => config('security.rate_limiting.login.attempts', 5),
                'decay_minutes' => config('security.rate_limiting.login.decay_minutes', 1),
                'message' => 'Too many login attempts. Please try again later.',
            ],
            'password-reset' => [
                'attempts' => config('security.rate_limiting.password_reset.attempts', 5),
                'decay_minutes' => config('security.rate_limiting.password_reset.decay_minutes', 1),
                'message' => 'Too many password reset requests. Please try again later.',
            ],
            'email-verification' => [
                'attempts' => config('security.rate_limiting.email_verification.attempts', 6),
                'decay_minutes' => config('security.rate_limiting.email_verification.decay_minutes', 1),
                'message' => 'Too many email verification requests. Please try again later.',
            ],
            'admin-actions' => [
                'attempts' => config('security.rate_limiting.admin_actions.attempts', 100),
                'decay_minutes' => config('security.rate_limiting.admin_actions.decay_minutes', 1),
                'message' => 'Too many admin actions. Please slow down.',
            ],
            'cart-actions' => [
                'attempts' => 30,
                'decay_minutes' => 1,
                'message' => 'Too many cart actions. Please slow down.',
            ],
            'search' => [
                'attempts' => 20,
                'decay_minutes' => 1,
                'message' => 'Too many search requests. Please slow down.',
            ],
        ];

        return $configs[$limiterName] ?? $configs['global'];
    }

    /**
     * Build the rate limit response.
     */
    protected function buildResponse(int $retryAfter, string $message): Response
    {
        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'retry_after' => $retryAfter,
            ], 429);
        }

        return response()->view('errors.429', [
            'message' => $message,
            'retry_after' => $retryAfter,
        ], 429);
    }
}