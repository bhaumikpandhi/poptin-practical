<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePollRequest;
use App\Http\Requests\Admin\UpdatePollRequest;
use App\Models\Poll;
use App\Repositories\PollRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PollController extends Controller
{
    public function __construct(
        private PollRepositoryInterface $pollRepository
    ) {}

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'created_at-desc');
        $ajax = $request->input('ajax', false);

        $polls = $this->pollRepository->getAdminListingData($search, $sort);

        // If AJAX request, return JSON response
        if ($ajax || $request->wantsJson()) {
            $pagination = $polls->hasPages() ? (string) $polls->links('pagination::bootstrap-4') : '';

            return response()->json([
                'rows' => view('admin.polls.partials.polls_table', compact('polls'))->render(),
                'pagination' => $pagination,
            ]);
        }

        return view('admin.polls.index', compact('polls'));
    }

    public function create()
    {
        return view('admin.polls.form');
    }

    public function store(StorePollRequest $request)
    {
        Gate::authorize('create', Poll::class);

        $data = $request->validated();

        $this->pollRepository->create($data, Auth::id());

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Poll created successfully!');
    }

    public function edit(Poll $poll)
    {
        Gate::authorize('update', $poll);

        $poll->load('options');

        return view('admin.polls.form', compact('poll'));
    }

    public function update(UpdatePollRequest $request, Poll $poll)
    {
        Gate::authorize('update', $poll);

        $data = $request->validated();
        $this->pollRepository->update($poll, $data);

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Poll updated successfully!');
    }

    public function destroy(Poll $poll)
    {
        Gate::authorize('delete', $poll);

        $this->pollRepository->delete($poll);

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Poll deleted successfully!');
    }
}
