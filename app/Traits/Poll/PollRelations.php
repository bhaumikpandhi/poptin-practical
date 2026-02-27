<?php

namespace App\Traits\Poll;

use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\User;

trait PollRelations
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }
}
