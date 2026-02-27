<?php

namespace App\Traits\Poll;

use Illuminate\Database\Eloquent\Builder;

trait PollScopes
{
    public function scopeByUser(Builder $query, $userId)
    {
        $query->where('user_id', $userId);
    }
}