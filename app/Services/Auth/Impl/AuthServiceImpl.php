<?php

namespace App\Services\Auth\Impl;

use App\DTO\USer\Auth\SignInData;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthServiceImpl implements AuthService
{
    /**
     * @return User|Model
     */
    public function getUser(): User|Model
    {
        return User::with('roles.permissions')->find(Auth::user()->id);
    }

    /**
     * @param SignInData $signInData
     * @return array
     * @throws AuthenticationException
     */
    public function signIn(SignInData $signInData): array
    {
        $user = User::with('roles.permissions')->whereEmail($signInData->email)->first();
        if (!isset($user)) {
            throw new AuthenticationException();
        }
        if (Hash::check($signInData->password, $user->password)) {
            $user->tokens()->whereName('api-token')->delete();
            $user->load(['roles.permissions']);
            return [
                'user' => $user,
                'token' => $user->createToken('api-token')->plainTextToken
            ];
        }
        throw new AuthenticationException();
    }

    /**
     * @param User $user
     * @return void
     */
    public function removeAuthenticationToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
