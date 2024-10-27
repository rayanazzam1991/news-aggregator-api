<?php

namespace Modules\UserPreferences\Repository;

use App\Enum\GeneralParamsEnum;
use App\Helpers\Eloquent\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\UserPreferences\Models\UserPreference;

class UserPreferenceRepository extends BaseRepository
{
    public function model(): string
    {
        return UserPreference::class;
    }

    public function searchWithPaginate(int $userId): LengthAwarePaginator
    {
        return $this->model::query()
            ->where(
                column: 'user_id',
                operator: '=',
                value: $userId)
            ->paginate(perPage: GeneralParamsEnum::PAGINATION_LIMIT->value);
    }

    public function searchAll(int $userId, int $limit): Collection
    {
        return $this->model::query()
            ->where(
                column: 'user_id',
                operator: '=',
                value: $userId)
            ->limit($limit)
            ->get();
    }
}
