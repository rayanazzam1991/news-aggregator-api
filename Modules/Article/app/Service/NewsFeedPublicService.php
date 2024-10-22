<?php

namespace Modules\Article\Service;

use Modules\Article\Contracts\NewsFeedPublicInterface;
use Modules\Article\Enums\PreferenceTypesEnum;
use Modules\Article\Filter\ArticleSearchFilter;
use Modules\Article\Repository\ArticleRepository;

readonly class NewsFeedPublicService implements NewsFeedPublicInterface
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    /**
     * @param array<int,array{
     *     preference_id:int,
     *     preference_type:string,
     * }> $request
     */
    public function getArticles(array $request): \Illuminate\Support\Collection
    {
        $result = collect();
        foreach ($request as $item) {
            $filter = $this->mapToArticleFilter($item);
            $articles = $this->articleRepository->searchWithLimit($filter, 10);
            $result = $result->merge($articles);
        }

        return $result;
    }

    /**
     * @param array{
     *     preference_id:int,
     *     preference_type:string,
     * } $request
     */
    private function mapToArticleFilter(array $request): ArticleSearchFilter
    {
        $type = $request['preference_type'];

        return match ($type) {
            PreferenceTypesEnum::AUTHOR->value => new ArticleSearchFilter(author_id: $request['preference_id']),
            PreferenceTypesEnum::CATEGORY->value => new ArticleSearchFilter(category_id: $request['preference_id']),
            PreferenceTypesEnum::SOURCE->value => new ArticleSearchFilter(source_id: $request['preference_id']),
            default => new ArticleSearchFilter,
        };
    }
}
