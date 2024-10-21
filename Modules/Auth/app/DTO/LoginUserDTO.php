<?php

namespace Modules\Auth\DTO;

use Spatie\LaravelData\Data;

class LoginUserDTO extends Data
{
    public function __construct(
        public string $email,
        public string $password
    ) {}

    /**
     * @param array{
     *    email:string,
     *    password:string
     * } $request
     */
    public static function fromRequest(array $request): LoginUserDTO
    {
        return new self(
            email: $request['email'],
            password: $request['password']
        );
    }
}
