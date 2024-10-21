<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\Auth\DTO\ResetPasswordUserDTO;
use Modules\Auth\Exceptions\PasswordResetException;
use Modules\Auth\Models\User;
use Symfony\Component\HttpFoundation\Response;

readonly class ResetPasswordAction
{
    /**
     * @throws PasswordResetException
     */
    public function handle(ResetPasswordUserDTO $resetPasswordUserDTO): string
    {
        $status = Password::reset(
            $resetPasswordUserDTO->toArray(),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ]);

                $user->save();

            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return __($status); // $status is valid here
        }

        // In case $status is not valid, return a default error message
        throw new PasswordResetException((string) __(is_string($status) ? $status : 'passwords.reset_failed'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
