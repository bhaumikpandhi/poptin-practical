<?php

namespace App\Traits\Poll;

use Illuminate\Database\Eloquent\Builder;

trait PollScopes
{
    public function scopeByUser(Builder $query, $userId)
    {
        $query->where('user_id', $userId);
    }

    public function scopeSearch(Builder $query, $value)
    {
        $query->where('question', 'like', '%' . $value . '%')
            ->orWhereHas('options', function (Builder $q) use ($value) {
                $q->where('option', 'like', '%' . $value . '%');
            });
    }
}