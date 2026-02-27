<?php

namespace App\Repositories\Auth;

interface RegisterRepositoryInterface
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function register(array $data);
}
