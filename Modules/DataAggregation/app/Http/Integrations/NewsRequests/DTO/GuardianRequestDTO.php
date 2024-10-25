<?php

namespace Modules\DataAggregation\Http\Integrations\NewsRequests\DTO;

use Spatie\LaravelData\Data;

class GuardianRequestDTO extends Data
{
    public function __construct(
        public string $apiKey
    )
    {}

}
