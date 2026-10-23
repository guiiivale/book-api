<?php

namespace App\Application\Books\DTOs;

final class UpdateBookDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $isbn,
        public readonly string $value,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: trim((string) $data['name']),
            isbn: (string) $data['isbn'],
            value: (string) $data['value'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'isbn' => $this->isbn,
            'value' => $this->value,
        ];
    }
}
