<?php

namespace App\Helpers\ApiResponse;

class SuccessResult extends Result
{
    public function __construct(?string $message, bool $isOk = true)
    {
        parent::__construct();
        $this->isOk = $isOk;
        $this->message = $message ?? 'Task Completed Successfully';

    }
}
