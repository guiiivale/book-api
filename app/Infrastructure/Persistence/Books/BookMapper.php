<?php

namespace App\Infrastructure\Persistence\Books;

use App\Domain\Books\Entities\Book;

final class BookMapper
{
    public static function toEntity(BookModel $model): Book
    {
        return new Book(
            id: $model->id,
            userId: (int) $model->user_id,
            name: $model->name,
            isbn: $model->isbn,
            value: (string) $model->value,
            createdAt: $model->created_at?->toIso8601String(),
            updatedAt: $model->updated_at?->toIso8601String(),
        );
    }
}
