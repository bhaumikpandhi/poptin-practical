@forelse($polls as $poll)
    <tr>
        <td class="ps-3">
            <div class="fw-medium">{{ Str::limit($poll->question, 60) }}</div>
        </td>
        <td>
            <span class="badge bg-info">{{ $poll->options_count ?? 0 }}</span>
        </td>
        <td>
            <span class="badge bg-success">{{ $poll->votes_count ?? 0 }}</span>
        </td>
        <td>
            <small class="text-muted">{{ $poll->created_at->format('d-M-Y') }}</small>
        </td>
        <td class="pe-3">
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('polls.show', $poll->id) }}" target="_blank" class="btn btn-outline-primary" title="Show">
                    <i class="bi bi-eye"></i>
                </a>
                @if($poll->votes_count == 0)
                    <a href="{{ route('admin.polls.edit', $poll->id) }}" class="btn btn-outline-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger delete-poll" data-poll-id="{{ $poll->id }}" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 2rem; color: #ccc;"></i>
            <p class="text-muted mt-3 mb-0">No polls found</p>
        </td>
    </tr>
@endforelse