<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    public function create(?User $user): bool
    {
        return $user->hasRole(RoleEnum::Admin->value);
    }

    public function update(User $user, Poll $poll): bool
    {
        return $user->hasRole(RoleEnum::Admin->value) && $user->id === $poll->user_id && $poll->votes()->count() === 0;
    }

    public function delete(User $user, Poll $poll): bool
    {
        return $user->hasRole(RoleEnum::Admin->value) && $user->id === $poll->user_id && $poll->votes()->count() === 0;
    }
}
