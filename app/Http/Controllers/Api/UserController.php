<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUser;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function index()
    {
        //
    }

    /**
     * @param StoreUser $request
     * @return UserResource
     */
    public function store(StoreUser $request): UserResource
    {
        $user = $this->userService->storeUser($request->validated());

        return new UserResource($user);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {

    }

    /**
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        //
    }
}
