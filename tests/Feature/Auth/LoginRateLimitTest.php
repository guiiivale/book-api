<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginRateLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'auth_limits.login.max_attempts' => 5,
            'auth_limits.login.decay_seconds' => 60,
        ]);
    }

    public function test_login_is_locked_after_five_failed_attempts_for_same_email_and_ip(): void
    {
        User::factory()->create([
            'email' => 'reader@example.com',
            'password' => Hash::make('secret123'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/auth/login', [
                'email' => 'reader@example.com',
                'password' => 'wrong-password',
            ])->assertUnauthorized();
        }

        $locked = $this->postJson('/api/auth/login', [
            'email' => 'reader@example.com',
            'password' => 'wrong-password',
        ]);

        $locked->assertStatus(429)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Too many attempts. Please try again later.')
            ->assertHeader('Retry-After');

        $this->postJson('/api/auth/login', [
            'email' => 'reader@example.com',
            'password' => 'secret123',
        ])->assertStatus(429);
    }

    public function test_successful_login_clears_failed_attempt_counter(): void
    {
        User::factory()->create([
            'email' => 'reader@example.com',
            'password' => Hash::make('secret123'),
        ]);

        for ($i = 0; $i < 4; $i++) {
            $this->postJson('/api/auth/login', [
                'email' => 'reader@example.com',
                'password' => 'wrong-password',
            ])->assertUnauthorized();
        }

        $this->postJson('/api/auth/login', [
            'email' => 'reader@example.com',
            'password' => 'secret123',
        ])->assertOk();

        $this->postJson('/api/auth/login', [
            'email' => 'reader@example.com',
            'password' => 'wrong-password',
        ])->assertUnauthorized();
    }
}
