<?php

namespace Modules\Article\Repository;

use App\Helpers\Eloquent\BaseRepository;
use Modules\Article\Models\Source;

class SourceRepository extends BaseRepository
{
    public function model(): string
    {
        return Source::class;
    }
}
