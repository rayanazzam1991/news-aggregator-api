<?php

namespace App\Helpers\ApiResponse;

enum EnumResult: int
{
    case Success = 1;
    case Not_Confirmed = 2;
    case Fail = 3;
    case NotFound = 4;

}
