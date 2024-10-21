<?php

namespace Modules\Auth\DTO;

use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Data;

class RegisterUserDTO extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {}

    /**
     * @param array{
     *    name:string,
     *    email:string,
     *    password:string
     * } $request
     */
    public static function fromRequest(array $request): RegisterUserDTO
    {
        return new self(
            name: $request['name'],
            email: $request['email'],
            password: $request['password']
        );
    }

    /**
     * @return array{
     *    name:string,
     *    email:string,
     *    password:string
     * }
     */
    public static function toModel(RegisterUserDTO $registerUserDTO): array
    {

        return [
            'name' => $registerUserDTO->name,
            'email' => $registerUserDTO->email,
            'password' => Hash::make($registerUserDTO->password),
        ];
    }
}
