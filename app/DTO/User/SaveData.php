<?php

namespace App\DTO\User;

use Spatie\LaravelData\Data;

class SaveData extends Data
{
    /**
     * @param string $email
     * @param string $name
     * @param string $password
     * @param string $confirm_password
     * @param string $phone
     * @param bool $terms_and_conditions
     */
    public function __construct(
        public string  $email,
        public string  $name,
        public string  $password,
        public string  $confirm_password,
        public string  $phone,
        public bool    $terms_and_conditions,
    )
    {
    }

    /**
     * @return string[]
     */
    public static function rules(): array
    {
        return [
            'address' => 'required',
            'country_id' => 'required',
            'document_number' => 'required|integer',
            'name' => 'required|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => ['required',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_-])[A-Za-z\d@$!%*?&_-]{8,}$/'],
            'confirm_password' => 'required|same:password',
            'terms_and_conditions' => 'required',
            'phone' => 'required|string',
            'photo' => 'string',
        ];
    }
}
