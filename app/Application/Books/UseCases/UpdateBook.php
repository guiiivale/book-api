<?php

namespace App\Application\Books\UseCases;

use App\Application\Books\DTOs\UpdateBookDTO;
use App\Domain\Books\Entities\Book;
use App\Domain\Books\Repositories\BookRepository;

final class UpdateBook
{
    public function __construct(private BookRepository $books) {}

    public function __invoke(int $bookId, int $userId, array $data): Book
    {
        $this->books->findOrFailForUser($bookId, $userId);
        $dto = UpdateBookDTO::fromArray($data);

        return $this->books->update($bookId, $dto->toArray());
    }
}
