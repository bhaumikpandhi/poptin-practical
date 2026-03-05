@extends('layouts.user.layout')

@section('title', $poll->question)

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <a href="{{ route('polls.index') }}" class="btn btn-outline-secondary btn-sm mb-4">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Polls
            </a>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card p-5">
                <h2 class="h4 fw-bold mb-4">{{ $poll->question }}</h2>
                
                <div id="poll-content">
                    @include('user.polls.partials.poll-results')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/app.js'])

    <script type="module">
        window.addEventListener('load', () => {
            window.Echo.channel('polls.{{ $poll->id }}')
                .listen('.vote.created', (e) => {
                    //console.log('Vote event received:', e);
                    refreshPollContent();
                });
        });

        function refreshPollContent() {
            fetch('{{ route('polls.results', $poll) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('poll-content').innerHTML = html;
            })
            .catch(err => console.error('Failed to refresh poll:', err));
        }
    </script>
@endpush