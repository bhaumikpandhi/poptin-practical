<?php

namespace App\Repositories\Auth;

use App\Enums\RoleEnum;
use App\Models\User;

class RegisterRepository implements RegisterRepositoryInterface
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return User
     */
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_verified_at' => now(),
        ]);

        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        } else {
            $user->assignRole(RoleEnum::User->value);
        }

        return $user;
    }
}
