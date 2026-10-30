<?php

namespace App\Infrastructure\Persistence\Books;

use App\Domain\Books\Entities\Book;
use App\Domain\Books\Exceptions\BookAccessDeniedException;
use App\Domain\Books\Repositories\BookRepository;
use App\Infrastructure\Persistence\Shared\BaseEloquentEntityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class BookEloquentRepository extends BaseEloquentEntityRepository implements BookRepository
{
    protected function modelClass(): string
    {
        return BookModel::class;
    }

    protected function toEntity(Model $model): object
    {
        /** @var BookModel $model */
        return BookMapper::toEntity($model);
    }

    protected function normalizeData(array $data): array
    {
        if (isset($data['name'])) {
            $data['name'] = trim((string) $data['name']);
        }

        if (isset($data['isbn'])) {
            $data['isbn'] = preg_replace('/\D+/', '', (string) $data['isbn']) ?? '';
        }

        if (isset($data['value'])) {
            $data['value'] = number_format((float) $data['value'], 2, '.', '');
        }

        return $data;
    }

    public function paginateForUser(int $userId, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        $paginator = BookModel::query()
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page', $page);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn (BookModel $model) => BookMapper::toEntity($model))
        );

        return $paginator;
    }

    public function findByIdForUser(int $id, int $userId): ?Book
    {
        $model = BookModel::query()
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();

        return $model ? BookMapper::toEntity($model) : null;
    }

    public function findOrFailForUser(int $id, int $userId): Book
    {
        $book = $this->findByIdForUser($id, $userId);

        if (! $book) {
            throw new BookAccessDeniedException();
        }

        return $book;
    }

    public function updateForUser(int $id, int $userId, array $data): Book
    {
        $payload = $this->normalizeData($data);

        $updated = BookModel::query()
            ->where('user_id', $userId)
            ->where('id', $id)
            ->update($payload);

        if (!$updated) {
            throw new BookAccessDeniedException();
        }

        $model = BookModel::query()
            ->where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        return BookMapper::toEntity($model);
    }

    public function deleteByIdForUser(int $id, int $userId): Book
    {
        $model = BookModel::query()
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (! $model) {
            throw new BookAccessDeniedException();
        }

        $entity = BookMapper::toEntity($model);
        $model->delete();

        return $entity;
    }
}
