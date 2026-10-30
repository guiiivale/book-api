<?php

namespace Tests\Feature\Http;

use Tests\TestCase;

class CorsTest extends TestCase
{
    public function test_preflight_allows_configured_client_origin(): void
    {
        config([
            'clients.allowed_origins' => ['https://app.example.com'],
            'cors.allowed_origins' => ['https://app.example.com'],
        ]);

        $response = $this->call(
            'OPTIONS',
            '/api/auth/login',
            [],
            [],
            [],
            [
                'HTTP_ORIGIN' => 'https://app.example.com',
                'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'POST',
            ]
        );

        $response->assertNoContent();
        $this->assertSame('https://app.example.com', $response->headers->get('Access-Control-Allow-Origin'));
    }

    public function test_preflight_rejects_unknown_origin(): void
    {
        config([
            'clients.allowed_origins' => ['https://app.example.com'],
            'cors.allowed_origins' => ['https://app.example.com'],
        ]);

        $response = $this->withHeaders([
            'Origin' => 'https://evil.example.com',
            'Access-Control-Request-Method' => 'POST',
        ])->options('/api/auth/login');

        $this->assertNotSame(
            'https://evil.example.com',
            $response->headers->get('Access-Control-Allow-Origin')
        );
    }
}
