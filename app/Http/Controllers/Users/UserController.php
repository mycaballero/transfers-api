<?php

namespace App\Http\Controllers\Users;


use App\DTO\User\SaveData;
use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService    $userService,
    )
    {
    }

    /**
     * @param Request $request
     * @return User
     */
    public function store(Request $request): User
    {
        return $this->userService->create(SaveData::from($request));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return User
     */
    public function update(Request $request, int $id): User
    {
        return $this->userService->update($id, SaveData::from($request));
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->userService->getUsers());
    }

}
