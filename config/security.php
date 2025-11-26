<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Security Settings
    |--------------------------------------------------------------------------
    |
    | These settings control various security aspects of the authentication
    | system including rate limiting, session management, and password policies.
    |
    */

    'rate_limiting' => [
        'login' => [
            'attempts' => env('LOGIN_THROTTLE_ATTEMPTS', 5),
            'decay_minutes' => env('LOGIN_THROTTLE_DECAY_MINUTES', 1),
        ],
        'password_reset' => [
            'attempts' => env('PASSWORD_RESET_THROTTLE_ATTEMPTS', 5),
            'decay_minutes' => env('PASSWORD_RESET_THROTTLE_DECAY_MINUTES', 1),
        ],
        'email_verification' => [
            'attempts' => 6,
            'decay_minutes' => 1,
        ],
        'admin_actions' => [
            'attempts' => 100,
            'decay_minutes' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for session security including regeneration intervals
    | and timeout settings for different types of operations.
    |
    */

    'session' => [
        'regeneration_interval' => 15 * 60, // 15 minutes
        'admin_timeout' => 30 * 60, // 30 minutes for admin sessions
        'user_timeout' => 2 * 60 * 60, // 2 hours for regular users
        'remember_me_timeout' => 30 * 24 * 60 * 60, // 30 days
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Security Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to password security including confirmation timeouts
    | and complexity requirements.
    |
    */

    'password' => [
        'confirmation_timeout' => env('AUTH_PASSWORD_TIMEOUT', 3600), // 1 hour
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | HTTP security headers that should be applied to responses.
    |
    */

    'headers' => [
        'x_frame_options' => env('APP_ENV') === 'production' ? 'DENY' : 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'hsts_max_age' => 31536000, // 1 year
        'hsts_include_subdomains' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Security Settings
    |--------------------------------------------------------------------------
    |
    | Additional security settings specifically for admin users.
    |
    */

    'admin' => [
        'require_password_confirmation' => true,
        'log_ip_changes' => true,
        'session_timeout' => 30 * 60, // 30 minutes
        'max_concurrent_sessions' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | Settings for security audit logging and monitoring.
    |
    */

    'audit' => [
        'log_failed_logins' => true,
        'log_admin_actions' => true,
        'log_ip_changes' => true,
        'retention_days' => 90,
    ],

];