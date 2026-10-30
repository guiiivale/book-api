<?php

namespace Tests\Feature\Books;

use App\Infrastructure\Persistence\Books\BookModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUser(): array
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        return [$user, $token];
    }

    public function test_user_can_create_and_list_own_books(): void
    {
        [$user, $token] = $this->actingAsUser();

        $create = $this->withToken($token)->postJson('/api/books', [
            'name' => 'Clean Code',
            'isbn' => '9780132350884',
            'value' => 59.99,
        ]);

        $create->assertCreated()
            ->assertJsonPath('data.name', 'Clean Code')
            ->assertJsonPath('data.isbn', '9780132350884');

        $list = $this->withToken($token)->getJson('/api/books');

        $list->assertOk()
            ->assertJsonPath('data.items.0.name', 'Clean Code')
            ->assertJsonPath('data.meta.total', 1);

        $this->assertDatabaseHas('books', [
            'user_id' => $user->id,
            'name' => 'Clean Code',
        ]);
    }

    public function test_user_cannot_read_update_or_delete_another_users_book(): void
    {
        [$userA, $tokenA] = $this->actingAsUser();
        $userB = User::factory()->create();

        $book = BookModel::query()->create([
            'user_id' => $userB->id,
            'name' => 'Private Book',
            'isbn' => '1234567890',
            'value' => 10,
        ]);

        $payload = [
            'name' => 'Hijacked Title',
            'isbn' => '9999999999',
            'value' => 99.99,
        ];

        $this->withToken($tokenA)
            ->getJson("/api/books/{$book->id}")
            ->assertNotFound()
            ->assertJsonPath('success', false);

        $this->withToken($tokenA)
            ->putJson("/api/books/{$book->id}", $payload)
            ->assertNotFound()
            ->assertJsonPath('message', 'Book not found.');

        $this->withToken($tokenA)
            ->deleteJson("/api/books/{$book->id}")
            ->assertNotFound();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'user_id' => $userB->id,
            'name' => 'Private Book',
            'isbn' => '1234567890',
        ]);
    }

    public function test_isbn_must_be_digits_only(): void
    {
        [, $token] = $this->actingAsUser();

        $this->withToken($token)->postJson('/api/books', [
            'name' => 'Invalid ISBN',
            'isbn' => '978-0',
            'value' => 10,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['isbn']);
    }

    public function test_user_can_update_and_delete_own_book(): void
    {
        [$user, $token] = $this->actingAsUser();

        $book = BookModel::query()->create([
            'user_id' => $user->id,
            'name' => 'Old Title',
            'isbn' => '1111111111',
            'value' => 5,
        ]);

        $this->withToken($token)->putJson("/api/books/{$book->id}", [
            'name' => 'New Title',
            'isbn' => '2222222222',
            'value' => 12.5,
        ])->assertOk()->assertJsonPath('data.name', 'New Title');

        $this->withToken($token)->deleteJson("/api/books/{$book->id}")
            ->assertOk();

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
