<?php

namespace Modules\UserPreferences\Repository;

use App\Enum\GeneralParamsEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Article\Repository\BaseRepository;
use Modules\UserPreferences\Models\UserPreference;

class UserPreferenceRepository extends BaseRepository
{


    public function model(): string
    {
        return UserPreference::class;
    }

    public function search(int $userId): LengthAwarePaginator
    {
        return $this->model::query()
            ->where(
                column: 'user_id',
                operator: '=',
                value: $userId)
            ->paginate(perPage: GeneralParamsEnum::PAGINATION_LIMIT->value);
    }
}
