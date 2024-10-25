<?php

namespace Modules\DataAggregation\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use JsonException;
use Modules\DataAggregation\Contracts\FetchNewsServiceInterface;
use Modules\DataAggregation\Http\Integrations\NewsConnector\GuardianNewsConnector;
use Modules\DataAggregation\Http\Integrations\NewsRequests\DTO\GuardianRequestDTO;
use Modules\DataAggregation\Http\Integrations\NewsRequests\FetchGuardianNewsRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

readonly class FetchGuardianNewsService implements FetchNewsServiceInterface
{
    public function __construct(
        private GuardianNewsConnector $connector
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    public function fetch(): array
    {
        $requestDTO = new GuardianRequestDTO(Config::get('news.guardian'));
        $request = new FetchGuardianNewsRequest($requestDTO);
        $response = $this->connector->send($request);
        $responseBody = $response->json();

        return $this->normalizeData($responseBody);
    }

    private function normalizeData($apiResponse): array
    {
        $articlesData = []; // Array to hold all article data for bulk insertion

        foreach ($apiResponse['response']['results'] ?? [] as $item) {

            $normalizedItem = [
                'title' => $item['webTitle'] ?? null, // Return null if 'webTitle' is missing
                'summary' => $item['webTitle'] ?? null, // Set summary to null if missing
                'news_url' => $item['webUrl'] ?? null, // Return null if 'webUrl' is missing
                'key_words' => $item['sectionName'] ?? null, // Return null if 'sectionName' is missing
                'category' => $item['pillarName'] ?? null, // Return null if 'pillarName' is missing
                'author' => 'The Guardian',
                'published_at' => isset($item['webPublicationDate'])
                    ? Carbon::parse($item['webPublicationDate'])->toDateTimeString()
                    : '', // Empty string if 'webPublicationDate' is missing
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];

            $articlesData[] = $normalizedItem;
        }

        return $articlesData;
    }
}
