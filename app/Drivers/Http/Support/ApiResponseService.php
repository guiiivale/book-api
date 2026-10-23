<?php

namespace App\Drivers\Http\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponseService
{
    protected function buildResponse(
        bool $success,
        string $message,
        mixed $data = null,
        int $statusCode = 200,
        array $meta = [],
        array $errors = []
    ): JsonResponse {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null || ! empty($data)) {
            $response['data'] = $data;
        }

        if ($meta !== []) {
            $response['meta'] = $meta;
        }

        if ($errors !== []) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    public function ok(mixed $data = null, string $message = 'Request completed successfully.'): JsonResponse
    {
        return $this->buildResponse(true, $message, $data, 200);
    }

    public function created(mixed $data = null, string $message = 'Created successfully.'): JsonResponse
    {
        return $this->buildResponse(true, $message, $data, 201);
    }

    public function fail(string $message, int $statusCode = 400, ?array $errors = null): JsonResponse
    {
        return $this->buildResponse(false, $message, null, $statusCode, errors: $errors ?? []);
    }

    public function paginated(LengthAwarePaginator $paginator, string $message = 'List returned successfully.'): JsonResponse
    {
        return $this->buildResponse(
            true,
            $message,
            $paginator->items(),
            200,
            [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ]
        );
    }
}
