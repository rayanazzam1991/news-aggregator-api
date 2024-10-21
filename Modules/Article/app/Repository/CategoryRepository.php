<?php

namespace Modules\Article\Repository;

use Modules\Article\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function model(): string
    {
        return Category::class;
    }
}
