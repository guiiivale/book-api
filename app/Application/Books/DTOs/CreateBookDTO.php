<?php

namespace App\Application\Books\DTOs;

final class CreateBookDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $name,
        public readonly string $isbn,
        public readonly string $value,
    ) {}

    public static function fromArray(int $userId, array $data): self
    {
        return new self(
            userId: $userId,
            name: trim((string) $data['name']),
            isbn: (string) $data['isbn'],
            value: (string) $data['value'],
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'name' => $this->name,
            'isbn' => $this->isbn,
            'value' => $this->value,
        ];
    }
}
