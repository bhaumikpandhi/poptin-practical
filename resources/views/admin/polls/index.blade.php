@extends('layouts.admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Polls</h1>
                    <p class="text-muted">Manage all your polls from here</p>
                </div>
                <a href="{{ route('admin.polls.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Create Poll
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="row mb-4 success-message">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search by keyword...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="sortBy">
                                <option value="created_at-desc">Latest First</option>
                                <option value="created_at-asc">Oldest First</option>
                                <option value="question-asc">Question (A-Z)</option>
                                <option value="question-desc">Question (Z-A)</option>
                                <option value="votes-desc">Most Votes</option>
                                <option value="votes-asc">Least Votes</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-secondary w-100" id="resetBtn">
                                <i class="bi bi-arrow-clockwise"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted fw-semibold ps-3" style="font-size:0.875rem;">Question</th>
                                <th class="text-muted fw-semibold" style="font-size:0.875rem;">Options</th>
                                <th class="text-muted fw-semibold" style="font-size:0.875rem;">Votes</th>
                                <th class="text-muted fw-semibold" style="font-size:0.875rem;">Created At</th>
                                <th class="text-muted fw-semibold pe-3" style="font-size:0.875rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pollsTableBody">
                            @include('admin.polls.partials.polls_table', ['polls' => $polls])
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-top {{ $polls->hasPages() ? 'py-3' : 'p-0' }}">
                    <div id="paginationContainer">
                        @if($polls->hasPages())
                            {{ $polls->links('pagination::bootstrap-4') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Poll</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete this poll? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput         = document.getElementById('searchInput');
    const sortBy              = document.getElementById('sortBy');
    const resetBtn            = document.getElementById('resetBtn');
    const tbody               = document.getElementById('pollsTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const deleteModal         = new bootstrap.Modal(document.getElementById('deleteModal'));

    let deletePollId = null;

    function debounce(fn, ms) {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
    }

    setTimeout(() => {
        const successAlert = document.querySelector('.success-message');
        if (successAlert) {
            successAlert.remove();
        }
    }, 2000);

    function fetchPolls(extraParams = {}) {
        const url    = new URL('{{ route("admin.polls.index") }}', window.location.origin);
        const search = searchInput.value.trim();
        const sort   = sortBy.value;

        if (search) url.searchParams.set('search', search);
        if (sort)   url.searchParams.set('sort',   sort);

        Object.entries(extraParams).forEach(([k, v]) => {
            if (v != null) url.searchParams.set(k, v);
        });

        url.searchParams.set('ajax', 'true');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept':           'application/json',
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Server error ' + res.status);
            return res.json();
        })
        .then(data => {
            tbody.innerHTML               = data.rows;
            paginationContainer.innerHTML = data.pagination ?? '';
            attachDeleteHandlers();
            attachPaginationHandlers();
        })
        .catch(err => console.error('Poll fetch error:', err));
    }

    const debouncedFetch = debounce(() => fetchPolls(), 400);

    function attachPaginationHandlers() {
        paginationContainer.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const page = new URL(this.href).searchParams.get('page');
                fetchPolls({ page });
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    }

    function attachDeleteHandlers() {
        tbody.querySelectorAll('.delete-poll').forEach(btn => {
            btn.addEventListener('click', function () {
                deletePollId = this.dataset.pollId;
                deleteModal.show();
            });
        });
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (!deletePollId) return;

        const form   = document.createElement('form');
        form.method  = 'POST';
        form.action  = `{{ url('admin/polls') }}/${deletePollId}`;

        const csrf   = document.createElement('input');
        csrf.type    = 'hidden';
        csrf.name    = '_token';
        csrf.value   = '{{ csrf_token() }}';

        const method  = document.createElement('input');
        method.type   = 'hidden';
        method.name   = '_method';
        method.value  = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    });

    searchInput.addEventListener('input',  debouncedFetch);
    sortBy.addEventListener('change',      () => fetchPolls());
    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        sortBy.value      = 'created_at-desc';
        fetchPolls();
    });

    attachDeleteHandlers();
    attachPaginationHandlers();
});
</script>
@endpush

@push('styles')
<style>
    /* Match card styling between search card and table card */
    .card {
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
    }

    .table > thead > tr > th:first-child,
    .table > tbody > tr > td:first-child { padding-left: 1.25rem; }

    .table > thead > tr > th:last-child,
    .table > tbody > tr > td:last-child  { padding-right: 1.25rem; }

    .table-hover tbody tr:hover { background-color: #f8f9fa; }

    .btn-group-sm .btn { padding: 0.35rem 0.5rem; font-size: 0.875rem; }

    .card .table-responsive:first-child .table thead tr:first-child th {
        border-top: none;
    }

    #paginationContainer .pagination { margin-bottom: 0; }
</style>
@endpush