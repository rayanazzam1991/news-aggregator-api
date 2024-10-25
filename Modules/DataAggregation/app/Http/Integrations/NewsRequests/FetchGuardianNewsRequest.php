<?php

namespace Modules\DataAggregation\Http\Integrations\NewsRequests;

use Modules\DataAggregation\Http\Integrations\NewsRequests\DTO\GuardianRequestDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class FetchGuardianNewsRequest extends Request
{
    public function __construct(private readonly GuardianRequestDTO $DTO) {}

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
        ];
    }

    public function defaultConfig(): array
    {
        return [
            'timeout' => 30,
        ];
    }
}
