<?php

namespace App\Application\Auth\DTOs;

final class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $deviceName,
    ) {}

    public static function fromArray(array $data, string $deviceName = 'api'): self
    {
        return new self(
            email: strtolower(trim((string) $data['email'])),
            password: (string) $data['password'],
            deviceName: $deviceName,
        );
    }
}
