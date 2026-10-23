<?php

namespace App\Domain\Books\Entities;

final class Book
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public string $name,
        public string $isbn,
        public string $value,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
    ) {}
}
