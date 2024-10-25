<?php

namespace Modules\DataAggregation\Http\Integrations\NewsConnector;

use Illuminate\Support\Facades\Lang;
use Saloon\Exceptions\SaloonException;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;

class NewsOrgApiConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'https://newsapi.org/v2/top-headlines';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [
            'timeout' => 3600,
        ];
    }

    /**
     * @throws SaloonException
     */
    public function hasRequestFailed(Response $response): ?bool
    {
        $res = json_decode($response->body());
        if ($response->status() == 200)
            return false;
        throw new SaloonException($res->message, $response->status());
    }
}
