<?php

namespace Modules\Article\Repository;

use App\Helpers\Eloquent\BaseRepository;
use Modules\Article\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function model(): string
    {
        return Category::class;
    }
}
