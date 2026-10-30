<?php

namespace App\Application\Auth\DTOs;

final class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $deviceName,
        public readonly string $ipAddress,
    ) {}

    public static function fromArray(array $data, string $deviceName = 'api', string $ipAddress = '0.0.0.0'): self
    {
        return new self(
            email: strtolower(trim((string) $data['email'])),
            password: (string) $data['password'],
            deviceName: $deviceName,
            ipAddress: $ipAddress,
        );
    }
}
