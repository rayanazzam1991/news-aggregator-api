<?php

namespace Modules\DataAggregation\Http\Integrations\NewsRequests;

use Modules\DataAggregation\Http\Integrations\NewsRequests\DTO\NTimesRequestDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class FetchNTimesNewsRequest extends Request
{
    public function __construct(private readonly NTimesRequestDTO $DTO) {}

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '';
    }

    protected function defaultQuery(): array
    {
        return [
            'api-key' => $this->DTO->apiKey,
            //            'begin_data' => Carbon::today()->format('Ymd'),
            //            'end_data' => Carbon::today()->format('Ymd')
        ];
    }

    public function defaultConfig(): array
    {
        return [
            'timeout' => 30,
        ];
    }
}
