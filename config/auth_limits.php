<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Failed authentication attempts (login, future register, etc.)
    |--------------------------------------------------------------------------
    |
    | After max_attempts failures for the same email + IP, the client must wait
    | decay_seconds before trying again.
    |
    */

    'login' => [
        'max_attempts' => (int) env('LOGIN_MAX_ATTEMPTS', 5),
        'decay_seconds' => (int) env('LOGIN_DECAY_SECONDS', 60),
    ],

    'register' => [
        'max_attempts' => (int) env('REGISTER_MAX_ATTEMPTS', 5),
        'decay_seconds' => (int) env('REGISTER_DECAY_SECONDS', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Public auth HTTP endpoints (middleware throttle)
    |--------------------------------------------------------------------------
    |
    | Broad IP limit for POST /api/auth/* routes exposed without a token.
    |
    */

    'public_auth_per_minute' => (int) env('PUBLIC_AUTH_REQUESTS_PER_MINUTE', 20),

    /*
    | Used only when the request IP cannot be determined (CLI/tests).
    | Production traffic should always send a real client IP (see TRUSTED_PROXIES).
    */
    'fallback_client_ip' => env('AUTH_FALLBACK_CLIENT_IP', '0.0.0.0'),

];
