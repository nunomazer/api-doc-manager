<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiService
{
    protected function response(JsonResource|null $data, string $message, int $status, bool $error = false): JsonResponse
    {
        $payload =[
            'message' => $message,
            'data' => $data->resolve() ?? [],
        ];

        if ($error) $payload['error'] = true;

        return response()->json($payload, $status);
    }

    public function successResponse(JsonResource|null $data, string $message = 'Success', int $status = 200): JsonResponse
    {
        return $this->successResponse($data, $message, $status);
    }

    public function createdResponse(JsonResource|null $data, string $message = 'Created'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    public function errorResponse(JsonResource|null $data, string $message = 'Server error'): JsonResponse
    {
        return $this->successResponse($data, $message, 500);
    }

    public function errorRecordNotFoundResponse(JsonResource|null $data, string $message = 'Record Not Found'): JsonResponse
    {
        return $this->successResponse($data, $message, 404);
    }
}
