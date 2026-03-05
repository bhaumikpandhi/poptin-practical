<?php

namespace App\Http\Controllers;

use App\Events\VoteCreated;
use App\Http\Requests\StoreVoteRequest;
use App\Models\Poll;
use App\Repositories\PollRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

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

        try {
            $recorded = $this->pollRepository->recordVote(
                $poll,
                $request->input('option'),
                $ip,
                $userId
            );

            if (! $recorded) {
                return back()->withErrors('You have already voted or the selected option is invalid.');
            }

            event(new VoteCreated($poll));

            return back()->with('success', 'Your vote has been recorded.');
        } catch (QueryException $e) {
            // Catch database-level constraint violations (unique index violations, etc.)
            if (Str::contains($e->getMessage(), ['UNIQUE', 'duplicate', 'Duplicate entry'])) {
                return back()->withErrors('You have already voted on this poll.');
            }

            // Re-throw the exception if it's not a duplicate vote error
            throw $e;
        }
    }

    public function results(Poll $poll)
    {
        $poll->load(['options' => function ($q) {
            $q->withCount('votes');
        }]);

        $ip = request()->ip();
        $userId = Auth::id();
        $hasVoted = $this->pollRepository->hasVoted($poll, $userId, $ip);
        $userVote = $hasVoted ? $this->pollRepository->getUserVote($poll, $userId, $ip) : null;
        $totalVotes = $poll->votes()->count();

        return view('user.polls.partials.poll-results', compact('poll', 'hasVoted', 'userVote', 'totalVotes'));
    }
}
