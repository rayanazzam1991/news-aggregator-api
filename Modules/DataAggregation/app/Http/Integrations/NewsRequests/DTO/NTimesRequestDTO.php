<?php

namespace Modules\DataAggregation\Http\Integrations\NewsRequests\DTO;

use Spatie\LaravelData\Data;

class NTimesRequestDTO extends Data
{
    public function __construct(
        public string $apiKey
    ) {}
}
