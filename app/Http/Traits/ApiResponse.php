<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data, string $message = 'Operation successful.', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @param mixed|null $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'data'    => null,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * @param LengthAwarePaginator $paginatedData
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponseWithPagination(LengthAwarePaginator $paginatedData, string $message = 'Operation successful.', int $statusCode = 200): JsonResponse
    {
        // The paginator object already has a 'data' key, so we merge its parts into our response structure.
        $responseData = $paginatedData->toArray();

        return response()->json([
            'success' => true,
            'data'    => $responseData['data'],
            'message' => $message,
            'meta'    => [
                'current_page' => $responseData['current_page'],
                'from' => $responseData['from'],
                'last_page' => $responseData['last_page'],
                'path' => $responseData['path'],
                'per_page' => $responseData['per_page'],
                'to' => $responseData['to'],
                'total' => $responseData['total'],
            ],
            'links'   => [
                'first' => $responseData['first_page_url'],
                'last' => $responseData['last_page_url'],
                'prev' => $responseData['prev_page_url'],
                'next' => $responseData['next_page_url'],
            ],
        ], $statusCode);
    }
}
