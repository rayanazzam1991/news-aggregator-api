<?php

namespace Modules\Auth\Service;

use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Contracts\UserPublicInterface;
use Modules\Auth\Repository\UserRepository;

readonly class UserPublicService implements UserPublicInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function validateUser(int $userId): bool
    {
        return $this->userRepository->exists($userId);
    }

    public function getUser(int $userId): Model
    {
        return $this->userRepository->getById($userId);
    }
}
