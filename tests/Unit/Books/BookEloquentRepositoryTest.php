<?php

namespace Tests\Unit\Books;

use App\Domain\Books\Exceptions\BookAccessDeniedException;
use App\Domain\Books\Repositories\BookRepository;
use App\Infrastructure\Persistence\Books\BookModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookEloquentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_for_user_fails_and_does_not_mutate_row_when_user_is_not_owner(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $book = BookModel::query()->create([
            'user_id' => $owner->id,
            'name' => 'Original Title',
            'isbn' => '1111111111',
            'value' => 10,
        ]);

        /** @var BookRepository $repository */
        $repository = app(BookRepository::class);

        try {
            $repository->updateForUser($book->id, $intruder->id, [
                'name' => 'Stolen Title',
                'isbn' => '9999999999',
                'value' => 99.99,
            ]);
            $this->fail('Expected BookAccessDeniedException when updating without ownership scope.');
        } catch (BookAccessDeniedException) {
        }

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'user_id' => $owner->id,
            'name' => 'Original Title',
            'isbn' => '1111111111',
        ]);
    }

    public function test_unscoped_update_would_mutate_any_row_by_id_if_misused(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $book = BookModel::query()->create([
            'user_id' => $owner->id,
            'name' => 'Original Title',
            'isbn' => '1111111111',
            'value' => 10,
        ]);

        /** @var BookRepository $repository */
        $repository = app(BookRepository::class);

        $repository->update($book->id, [
            'name' => 'Changed Without User Scope',
            'isbn' => '2222222222',
            'value' => 20,
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'user_id' => $owner->id,
            'name' => 'Changed Without User Scope',
        ]);

        $this->assertNotEquals($other->id, $owner->id);
    }
}
