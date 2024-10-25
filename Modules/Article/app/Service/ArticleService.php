<?php

namespace Modules\Article\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Article\Filter\ArticleSearchFilter;
use Modules\Article\Models\Article;
use Modules\Article\Models\Author;
use Modules\Article\Models\Category;
use Modules\Article\Models\Source;
use Modules\Article\Repository\ArticleRepository;
use Modules\DataAggregation\Enum\NewsSourcesEnum;

readonly class ArticleService
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private CacheKeyService $cacheKeyService
    )
    {
    }

    /**
     * @return LengthAwarePaginator<Article>
     */
    public function getArticlesList(ArticleSearchFilter $filter): LengthAwarePaginator
    {
        // Generate a unique cache key based on the filter parameters
        $cacheKey = $this->cacheKeyService->generateArticleCacheKey($filter);

        // Set the cache duration (e.g., 60 minutes)
        $cacheTTL = 60 * 60;

        // Attempt to retrieve cached results
        return Cache::tags('articles')->remember($cacheKey, $cacheTTL, function () use ($filter) {
            return $this->articleRepository->searchWithPagination($filter);
        });
    }


    public function getArticleDetails(int $id): Model
    {
        return $this->articleRepository->show($id);
    }

    public function receiveDataFromApi(string $stringNews): void
    {
        $jsonData = json_decode($stringNews, true);


        foreach ($jsonData as $key => &$item) {
            // Check if the news already exists in the database
            if ($this->checkNewsExisted($item)) {
                // If it exists, remove it from the array
                unset($jsonData[$key]);
                continue; // Skip to the next item
            }

            $item['source_id'] = $this->createOrGetSourceId(NewsSourcesEnum::NEW_YORK_TIMES->value);
            $item['author_id'] = $this->createOrGetAuthorId($item['author']);
            $item['category_id'] = $this->createOrGetCategoryId($item['category']);

            unset($item['author']);
            unset($item['category']);
        }
        if (!empty($jsonData))
            $this->articleRepository->insert($jsonData);

    }

    private function createOrGetSourceId(string $sourceName): int|null
    {
        if (empty($sourceName))
            return null;

        $source = Source::query()->where(column: 'name', operator: '=', value: $sourceName)->first();
        if ($source != null) {
            return $source->id;
        }
        return Source::query()->create([
            'name' => $sourceName
        ])->id;
    }

    private function createOrGetCategoryId(string $categoryName): int|null
    {
        if (empty($categoryName))
            return null;

        $category = Category::query()->where(column: 'name', operator: '=', value: $categoryName)->first();
        if ($category != null) {
            return $category->id;
        }
        return Category::query()->create(attributes: [
            'name' => $categoryName
        ])->id;
    }

    private function createOrGetAuthorId(string $authorName): int|null
    {
        if (empty($authorName))
            return null;

        $author = Author::query()->where(column: 'name', operator: '=', value: $authorName)->first();
        if ($author != null) {
            return $author->id;
        }
        return Author::query()->create(attributes: [
            'name' => $authorName
        ])->id;
    }

    function checkNewsExisted($newsData): bool
    {

        // Extract required fields from the news data
        $newsUrl = $newsData['news_url'];
        $title = $newsData['title'];
        $publicationDate = $newsData['published_at'];

        // Check if an article with the same URL or title already exists
        return Article::where('news_url', $newsUrl)
            ->orWhere(function ($query) use ($title, $publicationDate) {
                $query->where('title', $title)
                    ->whereDate('created_at', Carbon::parse($publicationDate)->toDateString());
            })
            ->exists();
    }

}
