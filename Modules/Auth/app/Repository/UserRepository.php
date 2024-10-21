<?php

namespace Modules\Auth\Repository;

use Modules\Auth\Models\User;

class UserRepository
{
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
