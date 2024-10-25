<?php

namespace Modules\Article\Service;

use Modules\Article\Filter\ArticleSearchFilter;

class CacheKeyService
{
    public function generateArticleCacheKey(ArticleSearchFilter $filter): string
    {
        // Customize this logic as needed to uniquely identify cache entries
        return 'articles:'.md5(serialize($filter));
    }
}
