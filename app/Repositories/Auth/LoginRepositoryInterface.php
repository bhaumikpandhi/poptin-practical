<?php

namespace App\Repositories\Auth;

interface LoginRepositoryInterface
{
    /**
     * Attempt to login user with credentials
     *
     * @param array $credentials
     * @return bool
     */
    public function attempt(array $credentials): bool;

    /**
     * Get the currently authenticated user
     *
     * @return \App\Models\User|null
     */
    public function getAuthenticatedUser();

    /**
     * Logout the current user
     *
     * @return void
     */
    public function logout(): void;
}
