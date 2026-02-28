@include('admin.polls.partials.polls_table', ['polls' => $polls])

<div id="paginationLinks">
    @if($polls->hasPages())
        {{ $polls->links('pagination::bootstrap-4') }}
    @endif
</div>