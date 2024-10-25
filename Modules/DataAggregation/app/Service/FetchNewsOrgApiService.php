<?php

namespace Modules\DataAggregation\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use JsonException;
use Modules\DataAggregation\Contracts\FetchNewsServiceInterface;
use Modules\DataAggregation\Http\Integrations\NewsConnector\NewsOrgApiConnector;
use Modules\DataAggregation\Http\Integrations\NewsRequests\DTO\NewsOrgApiRequestDTO;
use Modules\DataAggregation\Http\Integrations\NewsRequests\FetchNewsOrgApiRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

readonly class FetchNewsOrgApiService implements FetchNewsServiceInterface
{
    public function __construct(
        private NewsOrgApiConnector $connector
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    public function fetch()
    {

        $requestDTO = new NewsOrgApiRequestDTO(
            apiKey: Config::get('news.news_api'),
            sortBy: 'publishedAt',
            category: 'business');
        $request = new FetchNewsOrgApiRequest($requestDTO);
        $response = $this->connector->send($request);
        $responseBody = $response->json();

        return $this->normalizeData($responseBody);
    }

    private function normalizeData($apiResponse): array
    {
        $articlesData = []; // Array to hold all article data for bulk insertion

        foreach ($apiResponse['articles'] ?? [] as $item) {
            // Normalize the author field
            $author = $item['author'] ?? 'Unknown'; // Use 'Unknown' if author is not provided

            // Normalize the source field
            $sourceName = $item['source']['name'] ?? 'Unknown Source'; // Use 'Unknown Source' if not provided

            // Normalize title and description, ensuring fallback to empty string if not provided
            $title = $item['title'] ?? ''; // Default to empty string if title is missing
            $summary = $item['description'] ?? ''; // Default to empty string if description is missing

            // Build the normalized item
            $normalizedItem = [
                'title' => $title, // Maps to 'title' in your database
                'summary' => $summary, // Use description as summary
                'news_url' => $item['url'] ?? '', // Maps to news_url, default to empty string if missing
                'key_words' => $sourceName, // Use the source name as keywords or metadata
                'category' => $sourceName ?? 'News', // The category is not explicitly provided, default to 'News'
                'author' => $author, // Use the author field
                'published_at' => isset($item['publishedAt']) ? Carbon::parse($item['publishedAt'])->toDateTimeString() : '', // Maps to published_at
                'created_at' => Carbon::now()->toDateTimeString(), // Add timestamps for bulk insert
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];

            $articlesData[] = $normalizedItem;
        }

        return $articlesData;
    }
}
