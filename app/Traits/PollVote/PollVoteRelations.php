<?php

namespace App\Traits\PollVote;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;

trait PollVoteRelations
{
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function option()
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
