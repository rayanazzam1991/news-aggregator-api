<?php

namespace Modules\Article\Repository;

use App\Helpers\Eloquent\BaseRepository;
use Modules\Article\Models\Author;

class AuthorRepository extends BaseRepository
{
    public function model(): string
    {
        return Author::class;
    }
}
