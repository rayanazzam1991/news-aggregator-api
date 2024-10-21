<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Auth\DTO\LoginUserDTO;
use Modules\Auth\Models\User;
use Modules\Auth\Repository\UserRepository;

readonly class LoginAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * @throws ValidationException
     */
    public function handle(LoginUserDTO $loginUserDTO): User
    {
        $user = $this->userRepository->getByEmail($loginUserDTO->email);

        if (! $user || ! Hash::check($loginUserDTO->password, $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('token')->plainTextToken;

        $user['token'] = $token;
        $user['token_type'] = 'Bearer';

        return $user;
    }
}
