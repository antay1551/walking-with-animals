<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use JetBrains\PhpStorm\NoReturn;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    #[NoReturn] public function test_show(): void
    {
        $user = UserFactory::new()->create();

        $this->getJson(route('api.users.show', [$user]))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'created_at' => (string) $user->created_at,
                    'updated_at' => (string) $user->updated_at,
                ],
            ]);
    }
}
