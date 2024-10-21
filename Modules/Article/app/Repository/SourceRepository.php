<?php

namespace Modules\Article\Repository;

use Modules\Article\Models\Source;

class SourceRepository extends BaseRepository
{
    public function model(): string
    {
        return Source::class;
    }
}
