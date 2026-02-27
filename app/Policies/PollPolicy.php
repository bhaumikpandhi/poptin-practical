<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;

class PollPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return $user === null || !$user->hasRole(RoleEnum::Admin->value);
    }
}
