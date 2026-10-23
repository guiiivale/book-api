<?php

namespace App\Domain\Books\Repositories;

use App\Domain\Books\Entities\Book;
use App\Domain\Shared\Repositories\BaseEntityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @extends BaseEntityRepository<Book>
 */
interface BookRepository extends BaseEntityRepository
{
    public function paginateForUser(int $userId, int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findByIdForUser(int $id, int $userId): ?Book;

    public function findOrFailForUser(int $id, int $userId): Book;

    public function deleteByIdForUser(int $id, int $userId): Book;
}
