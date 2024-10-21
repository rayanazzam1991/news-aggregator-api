<?php

namespace Modules\Auth\DTO;

use Spatie\LaravelData\Data;

class ResetPasswordUserDTO extends Data
{
    public function __construct(
        public string $token,
        public string $email,
        public string $password,
        public string $password_confirmation
    ) {}

    /**
     * @param array{
     *    token:string,
     *    email:string,
     *    password:string,
     *    password_confirmation:string
     * } $request
     */
    public static function fromRequest(array $request): ResetPasswordUserDTO
    {
        return new self(
            token: $request['token'],
            email: $request['email'],
            password: $request['password'],
            password_confirmation: $request['password_confirmation'],
        );
    }
}
