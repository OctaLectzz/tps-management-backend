<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a successful JSON response.
     *
     * @param  array<string, mixed>  $meta
     */
    protected function successResponse(mixed $data = null, string $message = 'Success', int $statusCode = 200, array $meta = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (! empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param  array<string, mixed>|null  $errors
     */
    protected function errorResponse(string $message = 'Error', int $statusCode = 400, ?array $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a paginated JSON response with meta information.
     */
    protected function paginatedResponse(mixed $resource, string $message = 'Success'): JsonResponse
    {
        $paginated = $resource->resource;

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resource->items(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }
}
