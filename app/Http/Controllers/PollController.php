<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Repositories\PollRepositoryInterface;
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
        return view('user.polls.show', compact('poll'));
    }

    public function vote(Poll $poll)
    {
        Gate::authorize('create', Poll::class);
        
        $this->pollRepository->recordVote($poll);
        
        return back()->with('success', 'Your vote has been recorded.');
    }
}
