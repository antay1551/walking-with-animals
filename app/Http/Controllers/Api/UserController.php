<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUser;
use App\Http\Requests\User\UpdateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function store(StoreUser $request): UserResource
    {
        $user = $this->userService->storeUser($request->validated());

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UpdateUser $request, User $user)
    {
        $this->userService->updateUser($request->validated(), $user);
        return new UserResource($user->refresh());
    }

    public function destroy(User $user)
    {
        $user->delete();
        return new UserResource($user);
    }
}
