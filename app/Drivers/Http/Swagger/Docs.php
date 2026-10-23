<?php

namespace App\Drivers\Http\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Book Registry API",
 *     version="1.0.0",
 *     description="REST API for user-owned book records. Responses use the `{ success, message, data }` envelope."
 * )
 * @OA\Server(url="http://127.0.0.1:8000", description="Local API server (set APP_URL / L5_SWAGGER_CONST_HOST)")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum",
 *     description="Obtain a token from POST /api/auth/login. In Authorize, use: Bearer {access_token}"
 * )
 */
final class Docs {}
