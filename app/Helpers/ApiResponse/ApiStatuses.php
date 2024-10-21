<?php

namespace App\Helpers\ApiResponse;

enum ApiStatuses: string
{
    case INITIATED = 'INITIATED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case ABANDONED = 'ABANDONED';
    case CANCELLED = 'CANCELLED';
    case FAILED = 'FAILED';
    case DECLINED = 'DECLINED';
    case RESTRICTED = 'RESTRICTED';
    case CAPTURED = 'CAPTURED';
    case VOID = 'VOID';
    case TIMEDOUT = 'TIMEDOUT';
    case UNKNOWN = 'UNKNOWN';
}
