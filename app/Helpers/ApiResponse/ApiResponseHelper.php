<?php

namespace App\Helpers\ApiResponse;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseHelper
{
    public static function sendResponse(Result $result): JsonResponse
    {

        $response = [
            'success' => $result->isOk,
            'error_code' => $result->code,
            'message' => $result->message,
            'data' => $result->result ?? null,
            'pagination' => $result->paginate ?? null,
        ];
        if (env('APP_ENV') != 'production') {
            if ($result->exception != null) {
                $response['exception'] = $result->exception;
            }
        }

        return response()->json($response, (int) $result->code);
    }

    public static function sendErrorResponse(?Result $result = null): JsonResponse
    {

        $response = [
            'success' => $result->isOk ?? false,
            'error_code' => $result?->code,
            'message' => $result->message ?? 'Error',
            'data' => $result->result ?? null,
            'pagination' => $result->paginate ?? null,
        ];
        if (env('APP_ENV') != 'production') {
            if ($result?->exception != null) {
                $response['exception'] = $result->exception;
            }
        }

        return response()->json($response, $result?->code ?? Response::HTTP_BAD_REQUEST);
    }

    public static function sendSuccessResponse(?SuccessResult $result = null): JsonResponse
    {

        $response = [
            'success' => $result->isOk ?? true,
            'error_code' => null,
            'message' => $result->message ?? 'Success',
            'data' => $result->result ?? null,
            'pagination' => $result->paginate ?? null,
        ];
        if (env('APP_ENV') != 'production') {
            if ($result?->exception != null) {
                $response['exception'] = $result->exception;
            }
        }

        return response()->json($response, $result?->code ?? Response::HTTP_OK);
    }
}
