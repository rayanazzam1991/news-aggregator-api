<?php

namespace Modules\Article\Service;

use Modules\Article\Filter\ArticleSearchFilter;
use Modules\Article\Repository\ArticleRepository;

readonly class ArticleService
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    public function getArticlesList(ArticleSearchFilter $filter)
    {
        return $this->articleRepository->search($filter->toArray());
    }

    public function getArticleDetails(int $id)
    {
        return $this->articleRepository->show($id);
    }
}
