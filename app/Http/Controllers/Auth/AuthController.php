<?php

namespace App\Http\Controllers\Auth;

use App\DTO\User\Auth\SignInData;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\LoginResource;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * @param UserService $userService
     * @param AuthService $authService
     */
    public function __construct(
        protected UserService $userService,
        protected AuthService $authService
    )
    {
    }

    /**
     * @return Model
     */
    public function getUser(): Model
    {
        return $this->authService->getUser();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function signIn(Request $request): JsonResponse
    {
      return response()->json(new LoginResource($this->authService->signIn(SignInData::from($request))));
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->authService->removeAuthenticationToken(request()->user());
    }
}
