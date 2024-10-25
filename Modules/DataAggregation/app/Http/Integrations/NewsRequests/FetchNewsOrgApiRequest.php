<?php

namespace Modules\DataAggregation\Http\Integrations\NewsRequests;

use Modules\DataAggregation\Http\Integrations\NewsRequests\DTO\NewsOrgApiRequestDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class FetchNewsOrgApiRequest extends Request
{
    public function __construct(private readonly NewsOrgApiRequestDTO $DTO) {}

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
            'apiKey' => $this->DTO->apiKey,
            'sortBy' => $this->DTO->sortBy,
            'category' => $this->DTO->category,
        ];
    }

    public function defaultConfig(): array
    {
        return [
            'timeout' => 30,
        ];
    }
}
