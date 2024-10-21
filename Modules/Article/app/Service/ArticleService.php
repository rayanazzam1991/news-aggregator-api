<?php

namespace Modules\Article\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Article\Filter\ArticleSearchFilter;
use Modules\Article\Models\Article;
use Modules\Article\Repository\ArticleRepository;

readonly class ArticleService
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    /**
     * @return LengthAwarePaginator<Article>
     */
    public function getArticlesList(ArticleSearchFilter $filter): LengthAwarePaginator
    {
        return $this->articleRepository->search($filter->toArray());
    }

    public function getArticleDetails(int $id): Model
    {
        return $this->articleRepository->show($id);
    }
}
