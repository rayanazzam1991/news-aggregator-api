<?php

namespace Modules\Auth\Actions;

use Modules\Auth\DTO\RegisterUserDTO;
use Modules\Auth\Repository\UserRepository;

readonly class RegisterAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function handle(RegisterUserDTO $registerUserDTO): void
    {

        $this->userRepository->create(RegisterUserDTO::toModel($registerUserDTO));
    }
}
