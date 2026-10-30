<?php

/**
 * Browser / SPA origins allowed to call the API (CORS).
 * Native mobile apps use Bearer tokens and are not subject to CORS; they still
 * send their device IP for rate limiting when calling the API directly.
 *
 * APP_CLIENT_ORIGINS — comma-separated full origins, e.g.:
 *   https://app.example.com,https://www.example.com,http://localhost:3000
 *
 * APP_CLIENT_ORIGIN_PATTERNS — optional regex patterns (Laravel CORS), e.g.:
 *   #^https://.*\.example\.com$#
 */

$origins = array_values(array_filter(array_map(
    trim(...),
    explode(',', (string) env('APP_CLIENT_ORIGINS', 'http://localhost:3000'))
)));

$patterns = array_values(array_filter(array_map(
    trim(...),
    explode(',', (string) env('APP_CLIENT_ORIGIN_PATTERNS', ''))
)));

$statefulFromEnv = array_values(array_filter(array_map(
    trim(...),
    explode(',', (string) env('SANCTUM_STATEFUL_DOMAINS', ''))
)));

$statefulFromOrigins = array_values(array_filter(array_map(
    static function (string $origin): ?string {
        $host = parse_url($origin, PHP_URL_HOST);
        if (! is_string($host) || $host === '') {
            return null;
        }
        $port = parse_url($origin, PHP_URL_PORT);

        return $port ? "{$host}:{$port}" : $host;
    },
    $origins
)));

return [
    'allowed_origins' => $origins,
    'allowed_origin_patterns' => $patterns,
    'stateful_domains' => $statefulFromEnv !== [] ? $statefulFromEnv : $statefulFromOrigins,
];
