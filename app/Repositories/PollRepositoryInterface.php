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
