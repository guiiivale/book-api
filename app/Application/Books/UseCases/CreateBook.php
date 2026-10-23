<?php

namespace App\Application\Books\UseCases;

use App\Application\Books\DTOs\CreateBookDTO;
use App\Domain\Books\Entities\Book;
use App\Domain\Books\Repositories\BookRepository;

final class CreateBook
{
    public function __construct(private BookRepository $books) {}

    public function __invoke(int $userId, array $data): Book
    {
        $dto = CreateBookDTO::fromArray($userId, $data);

        return $this->books->create($dto->toArray());
    }
}
