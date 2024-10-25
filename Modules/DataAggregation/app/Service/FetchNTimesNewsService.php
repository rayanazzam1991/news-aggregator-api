<?php

namespace Modules\DataAggregation\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use JsonException;
use Modules\DataAggregation\Contracts\FetchNewsServiceInterface;
use Modules\DataAggregation\Http\Integrations\NewsConnector\NewYorkNewsConnector;
use Modules\DataAggregation\Http\Integrations\NewsRequests\DTO\NTimesRequestDTO;
use Modules\DataAggregation\Http\Integrations\NewsRequests\FetchNTimesNewsRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

readonly class FetchNTimesNewsService implements FetchNewsServiceInterface
{

    public function __construct(
        private NewYorkNewsConnector $connector
    )
    {

    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    public function fetch()
    {

        $requestDTO = new NTimesRequestDTO(apiKey: Config::get('news.ny_times'));
        $request = new FetchNTimesNewsRequest($requestDTO);
        $response = $this->connector->send($request);
        $responseBody = $response->json();
        return $this->normalizeData($responseBody);
    }


    private function normalizeData($apiResponse): array
    {
        $articlesData = [];

        foreach ($apiResponse['response']['docs'] ?? [] as $doc) {
            // Build the author's full name or set a default if missing
            $authorFullName = 'Unknown';
            if (!empty($doc['byline']['person']) && isset($doc['byline']['person'][0])) {
                $author = $doc['byline']['person'][0];
                $authorFullName = trim(
                    ($author['firstname'] ?? '') . ' ' .
                    ($author['middlename'] ?? '') . ' ' .
                    ($author['lastname'] ?? '')
                );
                // Remove extra spaces
                $authorFullName = preg_replace('/\s+/', ' ', $authorFullName);
            }

            // Limit keywords to a maximum of five values
            $keywords = array_column($doc['keywords'] ?? [], 'value');
            $keyWordsString = implode(', ', array_slice($keywords, 0, min(count($keywords), 5)));

            $articleData = [
                'title' => $doc['headline']['main'] ?? '', // Use empty string if missing
                'summary' => $doc['abstract'] ?? ($doc['snippet'] ?? ''), // Use snippet if abstract is missing
                'image_url' => isset($doc['multimedia'][0]['url']) ? 'https://www.nytimes.com/' . $doc['multimedia'][0]['url'] : null,
                'news_url' => $doc['web_url'] ?? '', // Use empty string if missing
                'key_words' => $keyWordsString,
                'category' => $doc['section_name'] ?? '', // Use empty string if missing
                'published_at' => isset($doc['pub_date']) ? Carbon::parse($doc['pub_date'])->toDateTimeString() : '', // Use empty string if missing
                'author' => $authorFullName ?: 'Unknown', // Set to 'Unknown' if empty after trimming
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];

            $articlesData[] = $articleData;
        }

        return $articlesData;
    }
}
