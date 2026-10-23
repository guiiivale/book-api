<?php

namespace App\Drivers\Http\Resources;

use App\Domain\Books\Entities\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Book */
class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Book $book */
        $book = $this->resource;

        return [
            'id' => $book->id,
            'name' => $book->name,
            'isbn' => $book->isbn,
            'value' => $book->value,
            'created_at' => $book->createdAt,
            'updated_at' => $book->updatedAt,
        ];
    }
}
