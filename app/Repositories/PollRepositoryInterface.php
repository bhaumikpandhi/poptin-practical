<?php

namespace App\Repositories;

interface PollRepositoryInterface
{
    /**
     * Get paginated polls
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function getPaginatedPolls(int $perPage = 10);

    /**
     * Get a poll by ID
     *
     * @param string $pollId
     * @return \App\Models\Poll
     */
    public function getPollById(string $pollId);

    /**
     * Record a vote on a poll
     *
     * @param \App\Models\Poll $poll
     * @return bool
     */
    public function recordVote($poll): bool;
}
