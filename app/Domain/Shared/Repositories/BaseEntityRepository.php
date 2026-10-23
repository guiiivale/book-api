<?php

namespace App\Domain\Shared\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @template TEntity
 */
interface BaseEntityRepository
{
    public function findById(int $id, bool $withTrashed = false, bool $onlyTrashed = false): mixed;

    public function findOrFail(int $id, bool $withTrashed = false, bool $onlyTrashed = false): mixed;

    public function paginate(array $filters = [], int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function create(array $data): mixed;

    public function update(int $id, array $data): mixed;

    public function deleteById(int $id): mixed;

    public function restoreById(int $id): mixed;
}
