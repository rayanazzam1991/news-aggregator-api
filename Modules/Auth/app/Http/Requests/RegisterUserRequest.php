<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, ValidationRule|string|Password>|string|ValidationRule>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase() // Makes the password require at least one uppercase and one lowercase letter.
                    ->letters() // Makes the password require at least one letter.
                    ->numbers() // Makes the password require at least one number.
                    ->symbols() // Makes the password require at least one symbol.
                    ->uncompromised(5) // Allow passwords that have appeared in breaches fewer than 5 times
            ],
            'password_confirmation' => ['required'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
