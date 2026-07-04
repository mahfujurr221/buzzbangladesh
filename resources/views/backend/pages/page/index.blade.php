@extends('backend.layouts.master')

@section('title', 'Dynamic Pages')

@section('content')

@can('list-page')
<x-modern.filter title="Filter Pages" icon="bx bx-search-alt" :resetUrl="route('pages.index')"
    :expanded="request()->anyFilled(['search'])" class="mb-4">
    <div class="col-md-6">
        <x-modern.input label="Search Keyword" name="search" placeholder="Search by title or slug..." :value="request('search')"
            icon="bx bx-search" />
    </div>
</x-modern.filter>

<x-modern.card title="Pages List" icon="bx bx-file-blank">
    <x-slot name="actions">
        @can('create-page')
        <x-modern.actions.button tag="a" href="{{ route('pages.create') }}" actionType="add" label="Create New Page" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Title', 'Slug / URL', 'Status', 'Actions']">
        @forelse ($pages as $key => $page)
        <tr>
            <td class="align-middle text-center" style="width: 5%;">{{ $loop->iteration + ($pages->currentPage() - 1) * $pages->perPage() }}</td>
            <td class="align-middle fw-bold" style="width: 30%;">
                {{ $page->title }}
            </td>
            <td class="align-middle" style="width: 35%;">
                <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded">/page/{{ $page->slug }}</code>
            </td>
            <td class="align-middle" style="width: 15%;">
                <div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input status-toggle" type="checkbox" role="switch"
                        data-id="{{ $page->id }}"
                        {{ $page->status ? 'checked' : '' }}
                        {{ !auth()->user()->can('edit-page') ? 'disabled' : '' }}>
                </div>
            </td>
            <td class="align-middle" style="width: 15%;">
                <div class="d-flex justify-content-center gap-2">
                    <x-modern.actions.button tag="a" href="{{ url('page/'.$page->slug) }}" target="_blank" icon="bx bx-show" outline variant="info" />
                    
                    @can('edit-page')
                    <x-modern.actions.button tag="a" href="{{ route('pages.edit', $page->id) }}" actionType="edit" outline />
                    @endcan
                    
                    @can('delete-page')
                    <form action="{{ route('pages.destroy', $page->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button type="submit" actionType="delete" outline 
                            onclick="return confirm('Are you sure you want to delete this page?')" />
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-5">
                <div class="text-muted mb-3">
                    <i class="bx bx-file-blank text-secondary opacity-50" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-bold text-secondary">No Pages Found</h5>
                <p class="text-muted">You haven't created any dynamic pages yet.</p>
                @can('create-page')
                <a href="{{ route('pages.create') }}" class="btn btn-primary mt-2">
                    <i class="bx bx-plus me-1"></i> Create Your First Page
                </a>
                @endcan
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <div class="mt-4">
        {{ $pages->withQueryString()->links() }}
    </div>

</x-modern.card>
@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to view this module.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.status-toggle').change(function() {
            var pageId = $(this).data('id');
            var isChecked = $(this).prop('checked');
            var originalState = !isChecked;
            var checkbox = $(this);

            $.ajax({
                url: "{{ route('pages.toggle-status') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: pageId
                },
                success: function(response) {
                    if(response.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    }
                },
                error: function(xhr) {
                    checkbox.prop('checked', originalState);
                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred while updating status.'
                    });
                }
            });
        });
    });
</script>
@endpush
