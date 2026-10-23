<?php

namespace App\Application\Shared\UseCases;

use App\Domain\Shared\Repositories\BaseEntityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseCrudEntityUseCase
{
    public function __construct(
        protected BaseEntityRepository $repository
    ) {}

    protected function paginate(array $filters, int $perPage, int $page): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage, $page);
    }

    protected function findById(int $id, bool $withTrashed = false, bool $onlyTrashed = false): mixed
    {
        return $this->repository->findById($id, $withTrashed, $onlyTrashed);
    }

    protected function findOrFail(int $id, bool $withTrashed = false, bool $onlyTrashed = false): mixed
    {
        return $this->repository->findOrFail($id, $withTrashed, $onlyTrashed);
    }

    protected function deleteById(int $id): mixed
    {
        return $this->repository->deleteById($id);
    }
}
