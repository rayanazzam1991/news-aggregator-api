<?php

namespace Modules\DataAggregation\Enum;

enum NewsSourcesEnum: string
{
    case NEW_YORK_TIMES = 'ny_times';
    case GUARDIAN = 'guardian';
    case NEWS_API = 'news_api';
}
