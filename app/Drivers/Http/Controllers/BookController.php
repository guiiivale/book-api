<?php

namespace App\Drivers\Http\Controllers;

use App\Application\Books\UseCases\CreateBook;
use App\Application\Books\UseCases\DeleteBook;
use App\Application\Books\UseCases\GetBook;
use App\Application\Books\UseCases\ListBooks;
use App\Application\Books\UseCases\UpdateBook;
use App\Domain\Books\Exceptions\BookAccessDeniedException;
use App\Drivers\Http\Requests\Books\ListBooksRequest;
use App\Drivers\Http\Requests\Books\StoreBookRequest;
use App\Drivers\Http\Requests\Books\UpdateBookRequest;
use App\Drivers\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BookController extends BaseController
{
    public function index(ListBooksRequest $request, ListBooks $useCase)
    {
        $userId = (int) $request->user()->id;
        $paginator = $useCase($userId, $request->perPage(), $request->page());

        return $this->response->ok([
            'items' => BookResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ], 'Books listed successfully.');
    }

    public function show(int $id, Request $request, GetBook $useCase)
    {
        try {
            $book = $useCase($id, (int) $request->user()->id);

            return $this->response->ok(new BookResource($book));
        } catch (BookAccessDeniedException $e) {
            return $this->response->fail($e->getMessage(), 404);
        }
    }

    public function store(StoreBookRequest $request, CreateBook $useCase)
    {
        $book = $useCase((int) $request->user()->id, $request->validated());

        return $this->response->created(new BookResource($book), 'Book created successfully.');
    }

    public function update(int $id, UpdateBookRequest $request, UpdateBook $useCase)
    {
        try {
            $book = $useCase($id, (int) $request->user()->id, $request->validated());

            return $this->response->ok(new BookResource($book), 'Book updated successfully.');
        } catch (BookAccessDeniedException $e) {
            return $this->response->fail($e->getMessage(), 404);
        }
    }

    public function destroy(int $id, Request $request, DeleteBook $useCase)
    {
        try {
            $book = $useCase($id, (int) $request->user()->id);

            return $this->response->ok(new BookResource($book), 'Book deleted successfully.');
        } catch (BookAccessDeniedException $e) {
            return $this->response->fail($e->getMessage(), 404);
        }
    }
}
