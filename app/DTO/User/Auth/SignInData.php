<?php

namespace App\DTO\User\Auth;

use Spatie\LaravelData\Data;

class SignInData extends Data
{
    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(
        public readonly string $email,
        public readonly string $password
    )
    {
    }

    /**
     * @return array
     */
    public static function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => [
                'required',
                'regex:/[@$!%*#?&+]/',
                'regex:/[0-9]/',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'min:8',
                'string',
            ]
        ];
    }

    /**
     * @return string[]
     */
    public static function messages(): array
    {
        return [
            'password.regex' => 'La constraseña debe incluir mayúsculas, dígitos y caracteres especiales.',
        ];
    }
}
