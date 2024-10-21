<?php

namespace Modules\Auth\Exceptions;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\ErrorResult;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetException extends Exception
{
    private ?int $statusCode;

    public function __construct(string $message, int $code, ?Exception $previous = null)
    {
        $this->statusCode = $code;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(): JsonResponse
    {
        return ApiResponseHelper::sendErrorResponse(new ErrorResult(
            message: $this->getMessage(),
            code: $this->statusCode ?? Response::HTTP_BAD_REQUEST,
            isOk: false
        ));
    }
}
