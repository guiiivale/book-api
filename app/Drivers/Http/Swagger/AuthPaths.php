<?php

namespace App\Drivers\Http\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/api/auth/login",
 *     @OA\Post(
 *         operationId="authLogin",
 *         tags={"Authentication"},
 *         summary="Login with email and password",
 *         @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/LoginRequest")),
 *         @OA\Response(response=200, description="Login successful"),
 *         @OA\Response(response=401, description="Invalid credentials"),
 *         @OA\Response(response=422, description="Validation error")
 *     )
 * )
 * @OA\PathItem(
 *     path="/api/auth/logout",
 *     @OA\Post(
 *         operationId="authLogout",
 *         tags={"Authentication"},
 *         summary="Logout (revoke current token)",
 *         security={{"bearerAuth": {}}},
 *         @OA\Response(response=200, description="Logout successful"),
 *         @OA\Response(response=401, description="Unauthenticated")
 *     )
 * )
 * @OA\PathItem(
 *     path="/api/auth/me",
 *     @OA\Get(
 *         operationId="authMe",
 *         tags={"Authentication"},
 *         summary="Current authenticated user",
 *         security={{"bearerAuth": {}}},
 *         @OA\Response(response=200, description="User profile"),
 *         @OA\Response(response=401, description="Unauthenticated")
 *     )
 * )
 */
final class AuthPaths {}
