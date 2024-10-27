<?php

namespace Modules\Article\Contracts;

use Illuminate\Support\Collection;
use Modules\Article\Models\Article;

interface NewsFeedPublicInterface
{
    /**
     * @param array<int,array{
     *      preference_id:int,
     *      preference_type:string,
     *  }> $request
     * @return Collection<int,Article>
     */
    public function getArticles(array $request): Collection;
}
