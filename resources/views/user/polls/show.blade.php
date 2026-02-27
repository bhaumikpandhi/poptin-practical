@extends('layouts.user.layout')

@section('title', $poll->question)

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <a href="{{ route('polls.index') }}" class="btn btn-outline-secondary btn-sm mb-4">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Polls
            </a>

            <div class="card p-5">
                <h2 class="h4 fw-bold mb-4">{{ $poll->question }}</h2>

                @unlessrole('admin')
                    <form method="POST" action="{{ route('polls.vote', $poll) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i>
                            Vote
                        </button>
                    </form>
                @endunlessrole
            </div>
        </div>
    </div>
@endsection
