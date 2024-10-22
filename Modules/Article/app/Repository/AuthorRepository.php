<?php

namespace Modules\Article\Repository;

use Modules\Article\Models\Author;

class AuthorRepository extends BaseRepository
{
    public function model(): string
    {
        return Author::class;
    }
}
