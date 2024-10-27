<?php

namespace Modules\Auth\Repository;

use App\Helpers\Eloquent\BaseRepository;
use Modules\Auth\Models\User;

class UserRepository extends BaseRepository
{
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param array{
     *    name:string,
     *    email:string,
     *    password:string
     * } $data
     */
    public function create(array $data): void
    {
        User::query()->create($data);
    }

    public function getByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }
}
