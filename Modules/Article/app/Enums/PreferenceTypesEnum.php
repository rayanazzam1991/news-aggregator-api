<?php

namespace Modules\Article\Enums;

use App\Traits\EnumValues;

enum PreferenceTypesEnum :string
{
    use EnumValues;
    case AUTHOR = 'author';
    case CATEGORY = 'category';
    case SOURCE = 'source';

}
