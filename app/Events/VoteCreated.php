<?php

namespace App\Events;

use App\Models\Poll;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class VoteCreated implements ShouldBroadcast
{
    public $poll;

    /**
     * Create a new event instance.
     */
    public function __construct(Poll $poll)
    {
        $this->poll = $poll;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('polls.' . $this->poll->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'vote.created';
    }

    public function broadcastWith(): array
    {
        $this->poll->loadCount('votes');
        $this->poll->load(['options' => function ($q) {
            $q->withCount('votes');
        }]);

        return [
            'poll_id' => $this->poll->id,
            'question' => $this->poll->question,
            'votes_count' => $this->poll->votes_count,
            'options' => $this->poll->options->map(fn($option) => [
                'id' => $option->id,
                'votes_count' => $option->votes_count,
            ])->toArray(),
        ];
    }
}
