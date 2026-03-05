<?php

namespace App\Repositories;

use App\Models\Poll;
use Illuminate\Support\Facades\Auth;

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
     * Record a vote on a poll for a given option.
     *
     * @param Poll $poll
     * @param string $optionId
     * @param string $ipAddress
     * @param int|null $userId
     * @return bool
     */
    public function recordVote($poll, string $optionId, string $ipAddress, ?string $userId = null): bool
    {
        if ($this->hasVoted($poll, $userId, $ipAddress)) {
            return false;
        }

        $option = $poll->options()->where('id', $optionId)->first();
        if (! $option) {
            return false;
        }

        $poll->votes()->create([
            'poll_option_id' => $option->id,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
        ]);

        return true;
    }

    /**
     * Get listing data for admin poll listing with search and sort
     *
     * @param string $search
     * @param string $sort
     * @param int $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function getAdminListingData($search = '', $sort = 'created_at-desc', $perPage = 10)
    {
        $query = Poll::ByUser(Auth::id())
            ->withCount(['options', 'votes'])
            ->when($search, function ($q) use ($search) {
                $q->search($search);
            })
            ->when($sort, function ($q) use ($sort) {
                [$sortField, $sortDirection] = explode('-', $sort);

                if ($sortField === 'votes') {
                    $q->orderBy('votes_count', $sortDirection);
                } else {
                    $q->orderBy($sortField, $sortDirection);
                }
            }, function ($q) {
                $q->latest('created_at');
            });

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Determine whether the given user or IP has already voted on the poll.
     *
     * @param Poll $poll
     * @param string|null $userId
     * @param string $ipAddress
     * @return bool
     */
    public function hasVoted($poll, ?string $userId, string $ipAddress): bool
    {
        $query = $poll->votes();

        if ($userId) {
            return $query
                ->where(function ($q) use ($userId, $ipAddress) {
                    $q->where('user_id', $userId)
                      ->orWhere(function ($q) use ($ipAddress) {
                          $q->whereNull('user_id')
                            ->where('ip_address', $ipAddress);
                      });
                })
                ->exists();
        }

        return $query->where('ip_address', $ipAddress)->exists();
    }

    /**
     * Retrieve the existing vote cast by the user or IP address, if any.
     *
     * @param Poll $poll
     * @param string|null $userId
     * @param string $ipAddress
     * @return \App\Models\PollVote|null
     */
    public function getUserVote($poll, ?string $userId, string $ipAddress)
    {
        $query = $poll->votes();

        if ($userId) {
            return $query->where('user_id', $userId)->first();
        }

        return $query->whereNull('user_id')->where('ip_address', $ipAddress)->first();
    }

    /**
     * Create a new poll with options
     *
     * @param array $data
     * @param int $userId
     * @return Poll
     */
    public function create($data, $userId)
    {
        $poll = Poll::create([
            'user_id' => $userId,
            'question' => $data['question'],
        ]);

        foreach ($data['options'] as $optionData) {
            $poll->options()->create([
                'option' => $optionData['text'],
            ]);
        }

        return $poll;
    }

    /**
     * Update an existing poll with options
     *
     * @param Poll $poll
     * @param array $data
     * @return Poll
     */
    public function update($poll, $data)
    {
        $poll->update([
            'question' => $data['question'],
        ]);

        $poll->options()->delete();
        
        foreach ($data['options'] as $optionData) {
            $poll->options()->create([
                'option' => $optionData['text'],
            ]);
        }
    }

    /**
     * Delete a poll
     *
     * @param Poll $poll
     * @return void
     */
    public function delete($poll)
    {
        $poll->delete();
    }
}
