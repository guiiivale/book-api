<?php

namespace App\Drivers\Http\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", example="demo@bookregistry.test"),
 *     @OA\Property(property="password", type="string", format="password", example="password")
 * )
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Demo User"),
 *     @OA\Property(property="email", type="string", example="demo@bookregistry.test")
 * )
 * @OA\Schema(
 *     schema="BookInput",
 *     required={"name", "isbn", "value"},
 *     @OA\Property(property="name", type="string", example="Clean Code"),
 *     @OA\Property(property="isbn", type="string", example="9780132350884"),
 *     @OA\Property(property="value", type="number", example=59.99)
 * )
 * @OA\Schema(
 *     schema="Book",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="isbn", type="string"),
 *     @OA\Property(property="value", type="string", example="59.99")
 * )
 */
final class Schemas {}
