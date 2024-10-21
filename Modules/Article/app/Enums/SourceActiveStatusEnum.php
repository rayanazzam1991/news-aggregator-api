<?php

namespace Modules\Article\Enums;

use App\Traits\EnumValues;

enum SourceActiveStatusEnum: int
{
    use EnumValues;
    case ACTIVE = 1;
    case IN_ACTIVE = 0;

}
