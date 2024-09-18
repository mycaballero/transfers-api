<?php

namespace App\Services\Auth;

use App\DTO\User\Auth\SignInData;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;

interface AuthService
{
    /**
     * @return User|Model
     */
    public function getUser(): User|Model;

    /**
     * @param SignInData $signInData
     * @return array
     * @throws AuthenticationException
     */
    public function signIn(SignInData $signInData): array;

    /**
     * @param User $user
     * @return void
     */
    public function removeAuthenticationToken(User $user): void;
}
