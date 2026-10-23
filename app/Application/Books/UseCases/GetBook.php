<?php

namespace App\Application\Books\UseCases;

use App\Domain\Books\Entities\Book;
use App\Domain\Books\Repositories\BookRepository;

final class GetBook
{
    public function __construct(private BookRepository $books) {}

    public function __invoke(int $bookId, int $userId): Book
    {
        return $this->books->findOrFailForUser($bookId, $userId);
    }
}
