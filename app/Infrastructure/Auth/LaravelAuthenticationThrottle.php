<?php

namespace App\Infrastructure\Auth;

use App\Application\Auth\Contracts\AuthenticationThrottle;
use Illuminate\Support\Facades\RateLimiter;

final class LaravelAuthenticationThrottle implements AuthenticationThrottle
{
    public function key(string $scope, string $email, string $ipAddress): string
    {
        return $scope.'|'.strtolower(trim($email)).'|'.$ipAddress;
    }

    public function tooManyAttempts(string $key, string $scope): bool
    {
        return RateLimiter::tooManyAttempts($key, $this->maxAttempts($scope));
    }

    public function hit(string $key, string $scope): void
    {
        RateLimiter::hit($key, $this->decaySeconds($scope));
    }

    public function clear(string $key): void
    {
        RateLimiter::clear($key);
    }

    public function availableIn(string $key): int
    {
        return RateLimiter::availableIn($key);
    }

    private function maxAttempts(string $scope): int
    {
        return (int) config("auth_limits.{$scope}.max_attempts", 5);
    }

    private function decaySeconds(string $scope): int
    {
        return (int) config("auth_limits.{$scope}.decay_seconds", 60);
    }
}
