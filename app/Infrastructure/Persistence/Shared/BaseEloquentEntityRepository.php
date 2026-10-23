<?php

namespace App\Infrastructure\Persistence\Shared;

use App\Domain\Shared\Repositories\BaseEntityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseEloquentEntityRepository implements BaseEntityRepository
{
    abstract protected function modelClass(): string;

    abstract protected function toEntity(Model $model): object;

    protected function normalizeData(array $data): array
    {
        return $data;
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        //
    }

    protected function newQuery(): Builder
    {
        $class = $this->modelClass();

        return (new $class)->newQuery();
    }

    public function findById(int $id, bool $withTrashed = false, bool $onlyTrashed = false): mixed
    {
        $query = $this->newQuery();
        $model = $query->getModel();

        if ($onlyTrashed && method_exists($model, 'onlyTrashed')) {
            $query->onlyTrashed();
        } elseif ($withTrashed && method_exists($model, 'withTrashed')) {
            $query->withTrashed();
        }

        $found = $query->find($id);

        return $found ? $this->toEntity($found) : null;
    }

    public function findOrFail(int $id, bool $withTrashed = false, bool $onlyTrashed = false): mixed
    {
        $entity = $this->findById($id, $withTrashed, $onlyTrashed);

        if (! $entity) {
            throw new \DomainException("Record {$id} not found.");
        }

        return $entity;
    }

    public function paginate(array $filters = [], int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        $query = $this->newQuery();
        $this->applyFilters($query, $filters);
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        if ($page > 1 && $paginator->lastPage() > 0 && $page > $paginator->lastPage()) {
            $query = $this->newQuery();
            $this->applyFilters($query, $filters);
            $paginator = $query->paginate($perPage, ['*'], 'page', 1);
        }

        $paginator->setCollection(
            $paginator->getCollection()->map(fn ($model) => $this->toEntity($model))
        );

        return $paginator;
    }

    public function create(array $data): mixed
    {
        $data = $this->normalizeData($data);
        $class = $this->modelClass();
        $model = new $class;
        $model->fill($data);
        $model->save();

        return $this->toEntity($model->fresh());
    }

    public function update(int $id, array $data): mixed
    {
        $data = $this->normalizeData($data);
        $class = $this->modelClass();
        $model = $class::query()->findOrFail($id);
        $model->fill($data);
        $model->save();

        return $this->toEntity($model->fresh());
    }

    public function deleteById(int $id): object
    {
        $model = $this->newQuery()->findOrFail($id);
        $entity = $this->toEntity($model);
        $model->delete();

        return $entity;
    }

    public function restoreById(int $id): object
    {
        $modelClass = $this->modelClass();
        $model = $modelClass::withTrashed()->findOrFail($id);
        $model->restore();

        return $this->toEntity($model->fresh());
    }
}
