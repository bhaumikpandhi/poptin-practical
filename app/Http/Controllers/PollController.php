<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Models\Poll;
use App\Repositories\PollRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PollController extends Controller
{
    public function __construct(
        private PollRepositoryInterface $pollRepository
    ) {}

    public function index()
    {
        $polls = $this->pollRepository->getPaginatedPolls(10);

        return view('user.polls.index', compact('polls'));
    }

    public function show(Poll $poll)
    {
        $poll->load(['options' => function ($q) {
            $q->withCount('votes');
        }]);

        $ip = request()->ip();
        $userId = Auth::id();
        $hasVoted = $this->pollRepository->hasVoted($poll, $userId, $ip);
        $userVote = $hasVoted ? $this->pollRepository->getUserVote($poll, $userId, $ip) : null;

        $totalVotes = $poll->votes()->count();

        return view('user.polls.show', compact('poll', 'hasVoted', 'userVote', 'totalVotes'));
    }

    public function vote(Poll $poll, StoreVoteRequest $request)
    {
        Gate::authorize('vote', $poll);

        $ip = $request->ip();
        $userId = Auth::id();

        $recorded = $this->pollRepository->recordVote(
            $poll,
            $request->input('option'),
            $ip,
            $userId
        );

        if (! $recorded) {
            return back()->withErrors('You have already voted or the selected option is invalid.');
        }

        return back()->with('success', 'Your vote has been recorded.');
    }
}
