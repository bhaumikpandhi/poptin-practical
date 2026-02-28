@extends('layouts.admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">{{ isset($poll) ? 'Edit Poll' : 'Create New Poll' }}</h1>
                    <p class="text-muted">{{ isset($poll) ? 'Modify your poll below' : 'Fill out the form to create a poll' }}</p>
                </div>
            </div>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.polls.index') }}">Polls</a></li>
                    <li class="breadcrumb-item active">{{ isset($poll) ? 'Edit' : 'Create' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="pollForm" method="POST" action="{{ isset($poll) ? route('admin.polls.update', $poll->id) : route('admin.polls.store') }}">
                        @csrf
                        @if(isset($poll))
                            @method('PUT')
                        @endif

                        @error('options')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="mb-4">
                            <label for="question" class="form-label fw-bold">
                                <i class="bi bi-chat-left-quote"></i>
                                Poll Question *
                            </label>
                            <textarea
                                class="form-control @error('question') is-invalid @enderror"
                                id="question"
                                name="question"
                                rows="3"
                                placeholder="Enter your poll question here..."
                                required
                            >{{ old('question', $poll->question ?? '') }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-list-ul"></i>
                                Poll Options *
                            </label>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle"></i>
                                <strong>Add at least 2 options</strong> to create a valid poll.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>

                            <div id="optionsContainer">
                                @if(old('options'))
                                    @foreach(old('options') as $index => $option)
                                        <div class="option-row mb-3" data-option-index="{{ $index }}">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-list-ol"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control option-input @error('options.' . $index . '.text') is-invalid @enderror"
                                                    name="options[{{ $index }}][text]"
                                                    placeholder="Enter option text"
                                                    value="{{ $option['text'] ?? '' }}"
                                                    required
                                                >
                                                <button type="button" class="btn btn-outline-danger remove-option">
                                                    <i class="bi bi-x-circle"></i> Remove
                                                </button>
                                            </div>
                                            @error('options.' . $index . '.text')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach

                                @elseif(isset($poll) && $poll->options->count() > 0)
                                    @foreach($poll->options as $index => $option)
                                        <div class="option-row mb-3" data-option-index="{{ $index }}">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-list-ol"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control option-input"
                                                    name="options[{{ $index }}][text]"
                                                    placeholder="Enter option text"
                                                    value="{{ $option->option }}"
                                                    required
                                                >
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger remove-option"
                                                    {{ $poll->options->count() <= 2 ? 'disabled' : '' }}
                                                >
                                                    <i class="bi bi-x-circle"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                @else
                                    @foreach([0, 1] as $index)
                                        <div class="option-row mb-3" data-option-index="{{ $index }}">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-list-ol"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control option-input"
                                                    name="options[{{ $index }}][text]"
                                                    placeholder="Enter option text"
                                                    required
                                                >
                                                <button type="button" class="btn btn-outline-danger remove-option" disabled>
                                                    <i class="bi bi-x-circle"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <button type="button" class="btn btn-outline-secondary mt-3" id="addOptionBtn">
                                <i class="bi bi-plus-circle"></i>
                                Add Option
                            </button>
                        </div>

                        <div class="d-flex gap-3 pt-4 border-top">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="bi bi-check-circle"></i>
                                {{ isset($poll) ? 'Update Poll' : 'Create Poll' }}
                            </button>
                            <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i>
                        Tips
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Clear Question</h6>
                        <p class="small text-muted">Write a clear and concise question that users can easily understand.</p>
                    </div>
                    <div class="mb-3">
                        <h6>Balanced Options</h6>
                        <p class="small text-muted">Provide balanced answer options to encourage meaningful responses.</p>
                    </div>
                    <div class="mb-3">
                        <h6>Minimum Options</h6>
                        <p class="small text-muted">You need at least 2 options to create a valid poll.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const optionsContainer = document.getElementById('optionsContainer');
    const addOptionBtn     = document.getElementById('addOptionBtn');
    const form             = document.getElementById('pollForm');

    addOptionBtn.addEventListener('click', function () {
        const index = optionsContainer.querySelectorAll('.option-row').length;
        const row   = document.createElement('div');
        row.className = 'option-row mb-3';
        row.dataset.optionIndex = index;
        row.innerHTML = `
            <div class="input-group">
                <span class="input-group-text bg-light">
                    <i class="bi bi-list-ol"></i>
                </span>
                <input
                    type="text"
                    class="form-control option-input"
                    name="options[${index}][text]"
                    placeholder="Enter option text"
                    required
                >
                <button type="button" class="btn btn-outline-danger remove-option">
                    <i class="bi bi-x-circle"></i> Remove
                </button>
            </div>
        `;
        optionsContainer.appendChild(row);
        updateRemoveButtons();
        reindexOptions();
    });

    optionsContainer.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.remove-option');
        if (removeBtn) {
            removeBtn.closest('.option-row').remove();
            updateRemoveButtons();
            reindexOptions();
        }
    });

    function reindexOptions() {
        optionsContainer.querySelectorAll('.option-row').forEach((row, i) => {
            row.dataset.optionIndex = i;
            row.querySelector('.option-input').name = `options[${i}][text]`;
        });
    }

    function updateRemoveButtons() {
        const rows = optionsContainer.querySelectorAll('.option-row');
        rows.forEach(row => {
            row.querySelector('.remove-option').disabled = rows.length <= 2;
        });
    }

    form.addEventListener('submit', function (e) {
        const question = document.getElementById('question').value.trim();
        const filled   = Array.from(optionsContainer.querySelectorAll('.option-input'))
                              .filter(i => i.value.trim() !== '');

        if (!question) {
            e.preventDefault();
            showAlert('Please enter a poll question.', 'warning');
            return;
        }
        if (filled.length < 2) {
            e.preventDefault();
            showAlert('Please add at least 2 non-empty options.', 'warning');
            return;
        }

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    });

    updateRemoveButtons();

    function showAlert(message, type = 'info') {
        form.querySelectorAll('.alert-dynamic').forEach(a => a.remove());
        const div = document.createElement('div');
        div.className = `alert alert-${type} alert-dismissible fade show alert-dynamic`;
        div.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        form.prepend(div);
    }
});
</script>
@endpush