<?php

namespace App\Repositories;

use App\Models\Poll;

class PollRepository implements PollRepositoryInterface
{
    /**
     * Get paginated polls
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function getPaginatedPolls(int $perPage = 10)
    {
        return Poll::simplePaginate($perPage);
    }

    /**
     * Get a poll by ID
     *
     * @param string $pollId
     * @return Poll
     */
    public function getPollById(string $pollId)
    {
        return Poll::findOrFail($pollId);
    }

    /**
     * Record a vote on a poll
     *
     * @param Poll $poll
     * @return bool
     */
    public function recordVote($poll): bool
    {
        // Business logic for recording a vote
        // This can be expanded based on your requirements
        return true;
    }
}
