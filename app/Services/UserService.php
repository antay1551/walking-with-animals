<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function storeUser(array $userAttributes): User
    {
        return User::query()->create(
            $userAttributes
        );
    }
}
