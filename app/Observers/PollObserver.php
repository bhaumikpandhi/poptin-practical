<?php

namespace App\Observers;

use App\Models\Poll;

class PollObserver
{
    public function deleted(Poll $poll): void
    {
        $poll->options()->delete();
        $poll->votes()->delete();
    }

    public function restored(Poll $poll): void
    {
        $poll->options()->restore();
        $poll->votes()->restore();
    }

    public function forceDeleted(Poll $poll): void
    {
        $poll->options()->forceDelete();
        $poll->votes()->forceDelete();
    }
}
