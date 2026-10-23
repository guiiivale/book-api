<?php

namespace App\Application\Books\UseCases;

use App\Domain\Books\Repositories\BookRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ListBooks
{
    public function __construct(private BookRepository $books) {}

    public function __invoke(int $userId, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->books->paginateForUser($userId, $perPage, $page);
    }
}
