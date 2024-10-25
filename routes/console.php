<?php

use Modules\DataAggregation\Enum\NewsSourcesEnum;
use Modules\DataAggregation\Jobs\FetchNewsJob;

Schedule::call(function () {
    FetchNewsJob::dispatch(NewsSourcesEnum::NEW_YORK_TIMES->value);
    FetchNewsJob::dispatch(NewsSourcesEnum::GUARDIAN->value)->delay(now()->addMinutes(10));
    FetchNewsJob::dispatch(NewsSourcesEnum::NEWS_API->value)->delay(now()->addMinutes(10));
})->everyFiveMinutes();
