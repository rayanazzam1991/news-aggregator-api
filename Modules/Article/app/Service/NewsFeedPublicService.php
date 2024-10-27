<?php

namespace Modules\Article\Service;

use Illuminate\Support\Collection;
use Modules\Article\Contracts\NewsFeedPublicInterface;
use Modules\Article\Enums\PreferenceTypesEnum;
use Modules\Article\Filter\ArticleSearchFilter;
use Modules\Article\Models\Article;
use Modules\Article\Repository\ArticleRepository;

readonly class NewsFeedPublicService implements NewsFeedPublicInterface
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    /**
     * Retrieve a collection of articles based on preferences.
     *
     * @param array<int, array{
     *     preference_id: int,
     *     preference_type: string
     * }> $request
     * @return Collection<int, Article>
     */
    public function getArticles(array $request): Collection
    {
        /** @var Collection<int, Article> $result */
        $result = collect();

        foreach ($request as $item) {
            $filter = $this->mapToArticleFilter($item);

            /** @var Collection<int, Article> $articles */
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
