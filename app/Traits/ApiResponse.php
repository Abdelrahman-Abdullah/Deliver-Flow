<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Success response
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Error response
     */
    protected function errorResponse(
        string $message = 'Error',
        mixed $errors = null,
        int $statusCode = 400
    ): JsonResponse {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $statusCode);
    }

    /**
     * Created response (201)
     */
    protected function createdResponse(
        mixed $data = null,
        string $message = 'Created successfully',
        int $statusCode = 201
    ): JsonResponse {
        return $this->successResponse($data, $message, $statusCode);
    }

    /**
     * No content response (204)
     */
    protected function deletedResponse(
        string $message = 'Deleted successfully'
    ): JsonResponse {
        return $this->successResponse(null, $message, 200);
    }

    /**
     * Unauthorized response (401)
     */
    protected function unauthorizedResponse(
        string $message = 'Unauthorized'
    ): JsonResponse {
        return $this->errorResponse($message, null, 401);
    }

    /**
     * Forbidden response (403)
     */
    protected function forbiddenResponse(
        string $message = 'Forbidden'
    ): JsonResponse {
        return $this->errorResponse($message, null, 403);
    }

    /**
     * Not found response (404)
     */
    protected function notFoundResponse(
        string $message = 'Not found'
    ): JsonResponse {
        return $this->errorResponse($message, null, 404);
    }
}