<?php

namespace App\Traits\PollOption;

use App\Models\PollVote;

trait PollOptionRelations
{
    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }
}
