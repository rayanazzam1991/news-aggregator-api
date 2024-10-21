<?php

namespace App\Helpers\ApiResponse;

class Result
{
    public bool $isOk = true;

    public string $message = '';

    public mixed $result;

    public mixed $paginate;

    public int $code = 200;

    public string $exception = '';

    /**
     * Result constructor.
     */
    public function __construct(mixed $result = null, mixed $paginate = null, ?string $message = null,
        bool $isOk = true, int $code = 200, string $exception = '')
    {
        $this->isOk = $isOk;
        $this->message = $message ?? 'Success';
        $this->result = $result;
        $this->paginate = $paginate;
        $this->code = $code;
        $this->exception = $exception;
    }
}
