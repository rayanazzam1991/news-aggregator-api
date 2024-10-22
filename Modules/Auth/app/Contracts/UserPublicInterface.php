<?php

namespace Modules\Auth\Contracts;

use Illuminate\Database\Eloquent\Model;

interface UserPublicInterface
{
    public function validateUser(int $userId): bool;

    public function getUser(int $userId): Model;
}
