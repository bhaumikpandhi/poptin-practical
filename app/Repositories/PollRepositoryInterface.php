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
    /**
     * Record a vote on a poll for a specific option.
     *
     * @param \App\Models\Poll $poll
     * @param string $optionId
     * @param string $ipAddress
     * @param string|null $userId
     * @return bool
     */
    public function recordVote($poll, string $optionId, string $ipAddress, ?string $userId = null): bool;

    /**
     * Get listing data for admin poll listing
     *
     * @param string $search
     * @param string $sort
     * @param int $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function getAdminListingData($search = '', $sort = 'created_at-desc', $perPage = 10);

    /**
     * Determine whether a user or an IP address has already voted on a poll.
     *
     * @param \App\Models\Poll $poll
     * @param int|null $userId
     * @param string $ipAddress
     * @return bool
     */
    public function hasVoted($poll, ?string $userId, string $ipAddress): bool;

    /**
     * Return the existing vote for a user or IP, if any.
     *
     * @param \App\Models\Poll $poll
     * @param int|null $userId
     * @param string $ipAddress
     * @return \App\Models\PollVote|null
     */
    public function getUserVote($poll, ?string $userId, string $ipAddress);

    /**
     * Create a new poll
     *
     * @param array $data
     * @param int $userId
     * @return \App\Models\Poll
     */
    public function create(array $data, int $userId);

    /**
     * Update an existing poll
     *
     * @param string $pollId
     * @param array $data
     * @return \App\Models\Poll
     */
    public function update(string $pollId, array $data);

    /**
     * Delete a poll
     *
     * @param \App\Models\Poll $poll
     * @return void
     */
    public function delete($poll);
}
