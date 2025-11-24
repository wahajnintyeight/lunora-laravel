<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Register custom middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'secure.session' => \App\Http\Middleware\SecureSession::class,
            'password.confirm' => \App\Http\Middleware\RequirePasswordConfirmation::class,
        ]);

        // Apply secure session middleware to web routes
        $middleware->web(append: [
            \App\Http\Middleware\SecureSession::class,
        ]);

        // Configure throttling for authentication routes
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
