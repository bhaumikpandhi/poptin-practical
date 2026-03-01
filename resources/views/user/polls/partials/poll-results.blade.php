@unlessrole('admin')
    @if(!$hasVoted)
        <form method="POST" action="{{ route('polls.vote', $poll) }}" id="vote-form">
            @csrf
            @foreach($poll->options as $option)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio"
                           name="option" id="option-{{ $option->id }}"
                           value="{{ $option->id }}">
                    <label class="form-check-label" for="option-{{ $option->id }}">
                        {{ $option->option }}
                    </label>
                </div>
            @endforeach

            @error('option')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary mt-3">
                <i class="bi bi-check-circle me-2"></i>
                Vote
            </button>
        </form>
    @else
        <p class="text-success mb-4">
            You have already voted for: <strong>{{ $userVote?->option?->option }}</strong>
        </p>
    @endif
@endunlessrole

<hr class="my-4">

@foreach($poll->options as $option)
    @php
        $count = $option->votes_count ?? 0;
        $percent = $totalVotes ? number_format($count * 100 / $totalVotes, 2) : '0.00';
    @endphp
    <div class="mb-3">
        <div class="d-flex justify-content-between mb-1">
            <span>{{ $option->option }}</span>
            <span>{{ $count }} ({{ $percent }}%)</span>
        </div>
        <div class="progress">
            <div class="progress-bar" role="progressbar"
                 style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}"
                 aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
@endforeach