<?php

namespace App\Drivers\Http\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/api/books",
 *     @OA\Get(
 *         operationId="listBooks",
 *         tags={"Books"},
 *         summary="List authenticated user's books",
 *         security={{"bearerAuth": {}}},
 *         @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
 *         @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
 *         @OA\Response(response=200, description="Paginated list"),
 *         @OA\Response(response=401, description="Unauthenticated")
 *     ),
 *     @OA\Post(
 *         operationId="createBook",
 *         tags={"Books"},
 *         summary="Create a book",
 *         security={{"bearerAuth": {}}},
 *         @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/BookInput")),
 *         @OA\Response(response=201, description="Book created"),
 *         @OA\Response(response=422, description="Validation error")
 *     )
 * )
 * @OA\PathItem(
 *     path="/api/books/{id}",
 *     @OA\Get(
 *         operationId="showBook",
 *         tags={"Books"},
 *         summary="Show a book",
 *         security={{"bearerAuth": {}}},
 *         @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *         @OA\Response(response=200, description="Book found"),
 *         @OA\Response(response=404, description="Not found")
 *     ),
 *     @OA\Put(
 *         operationId="updateBook",
 *         tags={"Books"},
 *         summary="Update a book",
 *         security={{"bearerAuth": {}}},
 *         @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *         @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/BookInput")),
 *         @OA\Response(response=200, description="Book updated"),
 *         @OA\Response(response=404, description="Not found")
 *     ),
 *     @OA\Delete(
 *         operationId="deleteBook",
 *         tags={"Books"},
 *         summary="Delete a book",
 *         security={{"bearerAuth": {}}},
 *         @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *         @OA\Response(response=200, description="Book deleted"),
 *         @OA\Response(response=404, description="Not found")
 *     )
 * )
 */
final class BookPaths {}
