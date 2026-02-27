<?php

namespace App\Repositories\Auth;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginRepository implements LoginRepositoryInterface
{
    /**
     * Attempt to login user with credentials
     *
     * @param array $credentials
     * @return bool
     */
    public function attempt(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * Get the currently authenticated user
     *
     * @return User|null
     */
    public function getAuthenticatedUser()
    {
        return Auth::user();
    }

    /**
     * Logout the current user
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
