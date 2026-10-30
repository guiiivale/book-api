<?php

namespace App\Application\Auth\Contracts;

interface AuthenticationThrottle
{
    public function key(string $scope, string $email, string $ipAddress): string;

    public function tooManyAttempts(string $key, string $scope): bool;

    public function hit(string $key, string $scope): void;

    public function clear(string $key): void;

    public function availableIn(string $key): int;
}
