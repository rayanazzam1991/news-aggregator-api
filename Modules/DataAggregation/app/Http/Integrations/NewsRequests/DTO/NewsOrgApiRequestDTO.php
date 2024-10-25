<?php

namespace Modules\DataAggregation\Http\Integrations\NewsRequests\DTO;

use Spatie\LaravelData\Data;

class NewsOrgApiRequestDTO extends Data
{
    public function __construct(
        public string $apiKey,
        public string $sortBy,
        public string $category,

    ) {}
}
