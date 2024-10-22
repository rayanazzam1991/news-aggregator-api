<?php

namespace Modules\Article\Contracts;

interface NewsFeedPublicInterface
{
    public function getArticles(array $request);
}
