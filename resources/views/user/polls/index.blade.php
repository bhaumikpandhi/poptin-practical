@extends('layouts.user.layout')

@section('title', 'Polls')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="mb-4">
                <h1 class="h3 fw-bold mb-1">Available Polls</h1>
                <p class="text-muted mb-4">Vote on polls and share your opinions</p>
            </div>

            @if($polls->count() > 0)
                <div class="space-y-3">
                    @foreach($polls as $poll)
                        <div class="card p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="card-title fw-bold mb-3">{{ $poll->question }}</h5>
                                    <a href="{{ route('polls.show', $poll) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-arrow-right me-2"></i>
                                        View Poll
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $polls->links() }}
                </div>
            @else
                <div class="card text-center p-5">
                    <i class="bi bi-inbox display-6 text-muted mb-3"></i>
                    <p class="text-muted mb-0">No polls available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
