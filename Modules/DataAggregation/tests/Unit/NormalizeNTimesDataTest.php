<?php

use Illuminate\Support\Facades\Http;
use Modules\DataAggregation\Http\Integrations\NewsConnector\GuardianNewsConnector;
use Modules\DataAggregation\Http\Integrations\NewsConnector\NewsOrgApiConnector;
use Modules\DataAggregation\Http\Integrations\NewsConnector\NewYorkNewsConnector;
use Modules\DataAggregation\Http\Integrations\NewsRequests\FetchGuardianNewsRequest;
use Modules\DataAggregation\Http\Integrations\NewsRequests\FetchNewsOrgApiRequest;
use Modules\DataAggregation\Http\Integrations\NewsRequests\FetchNTimesNewsRequest;
use Modules\DataAggregation\Service\FetchGuardianNewsService;
use Modules\DataAggregation\Service\FetchNewsOrgApiService;
use Modules\DataAggregation\Service\FetchNTimesNewsService;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Illuminate\Support\Facades\Config;


describe('Test Guardian Normalize Function', function () {

    beforeEach(function () {
        // Set the configuration directly
        Config::set('news.guardian', 'mock-api-key');
    });

    test('normalizes data correctly with complete fields', function () {

        MockClient::global([
            FetchGuardianNewsRequest::class => MockResponse::make([
                'response' => [
                    'results' => [
                        [
                            'webTitle' => 'Guardian Article 1',
                            'webUrl' => 'https://guardian.com/article1',
                            'sectionName' => 'World',
                            'pillarName' => 'News',
                            'webPublicationDate' => '2024-10-25T08:00:00Z',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $fetchService = new FetchGuardianNewsService(new GuardianNewsConnector());
        $result = $fetchService->fetch();

        expect($result)->toHaveCount(1)
            ->and($result[0])->toMatchArray([
                'title' => 'Guardian Article 1',
                'summary' => 'Guardian Article 1',
                'news_url' => 'https://guardian.com/article1',
                'key_words' => 'World',
                'category' => 'News',
                'published_at' => '2024-10-25 08:00:00',
            ]);
    });

    test('handles missing fields gracefully', function () {

        MockClient::global([
            FetchGuardianNewsRequest::class => MockResponse::make([
                'response' => [
                    'results' => [
                        [
                            'webTitle' => null,
                            'webUrl' => 'https://guardian.com/article2',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $fetchService = new FetchGuardianNewsService(new GuardianNewsConnector());
        $result = $fetchService->fetch();

        expect($result)->toHaveCount(1)
            ->and($result[0])->toMatchArray([
                'title' => null,
                'summary' => null,
                'news_url' => 'https://guardian.com/article2',
                'key_words' => null,
                'category' => null,
                'published_at' => '',
            ]);
    });
    test('returns an empty array if no docs are provided', function () {
        MockClient::global([
            FetchGuardianNewsRequest::class => MockResponse::make([
                'response' => [
                    'results' => [],
                ],
            ], 200),
        ]);

        $fetchService = new FetchGuardianNewsService(new GuardianNewsConnector());
        $result = $fetchService->fetch();

        expect($result)->toBeArray()->and($result)->toBeEmpty();
    });

});
describe('Test New York Times Normalize Function', function () {

    beforeEach(function () {
        // Set the configuration directly
        Config::set('news.ny_times', 'mock-api-key');
    });

    test('normalizes data correctly with complete fields', function () {

        MockClient::global([
            FetchNTimesNewsRequest::class => MockResponse::make([
                'response' => [
                    'docs' => [
                        [
                            'headline' => ['main' => 'NYTimes Article 1'],
                            'web_url' => 'https://nytimes.com/article1',
                            'section_name' => 'Politics',
                            'pub_date' => '2024-10-25T10:00:00Z',
                            'byline' => ['person' => [['firstname' => 'John', 'lastname' => 'Doe']]],
                            'keywords' => array_fill(0, 6, ['value' => 'Keyword']),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $fetchService = new FetchNTimesNewsService(new NewYorkNewsConnector());
        $result = $fetchService->fetch();

        expect($result)->toHaveCount(1)
            ->and($result[0])->toMatchArray([
                'title' => 'NYTimes Article 1',
                'summary' => '',
                'news_url' => 'https://nytimes.com/article1',
                'key_words' => 'Keyword, Keyword, Keyword, Keyword, Keyword',
                'category' => 'Politics',
                'published_at' => '2024-10-25 10:00:00',
                'author' => 'John Doe',
            ]);
    });

    test('handles missing fields gracefully', function () {

        MockClient::global([
            FetchNTimesNewsRequest::class => MockResponse::make([
                'response' => [
                    'docs' => [
                        [
                            'headline' => [],
                            'web_url' => 'https://nytimes.com/article2',
                            'keywords' => [],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $fetchService = new FetchNTimesNewsService(new NewYorkNewsConnector());
        $result = $fetchService->fetch();

        expect($result)->toHaveCount(1)
            ->and($result[0])->toMatchArray([
                'title' => '',
                'summary' => '',
                'news_url' => 'https://nytimes.com/article2',
                'key_words' => '',
                'category' => '',
                'published_at' => '',
                'author' => 'Unknown',
            ]);
    });

    test('returns an empty array if no docs are provided', function () {

        MockClient::global([
            FetchNTimesNewsRequest::class => MockResponse::make([
                'response' => ['docs' => []],
            ], 200),
        ]);

        $fetchService = new FetchNTimesNewsService(new NewYorkNewsConnector());
        $result = $fetchService->fetch();

        expect($result)->toBeArray()->and($result)->toBeEmpty();
    });
});
describe('Test News org Api Normalize Function', function () {
    beforeEach(function () {
        // Set the configuration directly
        Config::set('news.news_api', 'mock-api-key');
    });
    test('normalizes data correctly with complete fields', function () {

        MockClient::global([
            FetchNewsOrgApiRequest::class => MockResponse::make([
                'articles' => [
                    [
                        'title' => 'NewsOrg Article 1',
                        'description' => 'Summary of NewsOrg Article 1',
                        'url' => 'https://newsapi.org/article1',
                        'source' => ['name' => 'NewsOrg'],
                        'publishedAt' => '2024-10-25T12:00:00Z',
                    ],
                ],
            ], 200),
        ]);

        $fetchService = new FetchNewsOrgApiService(new NewsOrgApiConnector());
        $result = $fetchService->fetch();

        expect($result)->toHaveCount(1)
            ->and($result[0])->toMatchArray([
                'title' => 'NewsOrg Article 1',
                'summary' => 'Summary of NewsOrg Article 1',
                'news_url' => 'https://newsapi.org/article1',
                'key_words' => 'NewsOrg',
                'category' => 'NewsOrg',
                'published_at' => '2024-10-25 12:00:00',
                'author' => 'Unknown',
            ]);
    });

    test('handles missing fields gracefully', function () {

        MockClient::global([
            FetchNewsOrgApiRequest::class => MockResponse::make([
                'articles' => [
                    [
                        'title' => null,
                        'url' => 'https://newsapi.org/article2',
                    ],
                ],
            ], 200),
        ]);

        $fetchService = new FetchNewsOrgApiService(new NewsOrgApiConnector());
        $result = $fetchService->fetch();

        expect($result)->toHaveCount(1)
            ->and($result[0])->toMatchArray([
                'title' => '',
                'summary' => '',
                'news_url' => 'https://newsapi.org/article2',
                'key_words' => 'Unknown Source',
                'category' => 'Unknown Source',
                'published_at' => '',
                'author' => 'Unknown',
            ]);
    });

    test('returns an empty array if no docs are provided', function () {

        MockClient::global([
            FetchNewsOrgApiRequest::class => MockResponse::make([
                'articles' => [],
            ], 200),
        ]);

        $fetchService = new FetchNewsOrgApiService(new NewsOrgApiConnector());
        $result = $fetchService->fetch();

        expect($result)->toBeArray()->and($result)->toBeEmpty();
    });
});
