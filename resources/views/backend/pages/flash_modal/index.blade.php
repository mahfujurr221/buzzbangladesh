@extends('backend.layouts.master')
@section('title', 'Flash Modals')

@section('content')

@can('list-flash-modal')
<x-modern.card title="Flash Modals" icon="bx bx-airplay">
    <x-slot name="actions">
        @can('create-flash-modal')
        <x-modern.actions.button tag="a" href="{{ route('flash-modals.create') }}" actionType="add" label="Add Modal" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Image', 'Title', 'Popup Delay', 'Session Period', 'Status', 'Actions']">
        @forelse($flashModals as $modal)
        <tr>
            <td class="align-middle text-center">{{ $loop->iteration + ($flashModals->currentPage() - 1) * $flashModals->perPage() }}</td>
            <td class="align-middle">
                <a href="{{ asset($modal->image) }}" target="_blank" title="Click to view full image">
                    <img src="{{ asset($modal->image) }}" alt="Banner" class="rounded" style="height: 60px; width: 120px; object-fit: cover; border: 1px solid #e9ecef;">
                </a>
            </td>
            <td class="align-middle">
                <span class="fw-bold">{{ $modal->title ?? '—' }}</span>
                @if($modal->link)
                    <div class="small"><a href="{{ $modal->link }}" target="_blank" class="text-primary text-decoration-underline"><i class="bx bx-link me-1"></i>Link</a></div>
                @endif
            </td>
            <td class="align-middle text-center">
                <span class="badge bg-light text-dark border">{{ $modal->delay_seconds }}s</span>
            </td>
            <td class="align-middle">
                <div class="small text-muted mb-1"><i class="bx bx-calendar-check me-1 text-success"></i>{{ $modal->start_date->format('d M Y, h:i A') }}</div>
                <div class="small text-muted"><i class="bx bx-calendar-x me-1 text-danger"></i>{{ $modal->end_date->format('d M Y, h:i A') }}</div>
            </td>
            <td class="align-middle">
                @php $statusLabel = $modal->status_label; @endphp
                @if($statusLabel === 'active')
                    <span class="badge bg-success-subtle text-success border border-success-subtle">🟢 Active</span>
                @elseif($statusLabel === 'upcoming')
                    <span class="badge bg-info-subtle text-info border border-info-subtle">🔵 Upcoming</span>
                @elseif($statusLabel === 'expired')
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">🔴 Expired</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">⚫ Disabled</span>
                @endif
            </td>
            <td class="align-middle text-center">
                <div class="d-flex gap-2 justify-content-center">
                    @can('edit-flash-modal')
                    <x-modern.actions.button tag="a" href="{{ route('flash-modals.edit', $modal->id) }}" actionType="edit" outline />
                    @endcan
                    @can('delete-flash-modal')
                    <form action="{{ route('flash-modals.destroy', $modal->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button type="button" actionType="delete" outline class="delete-btn" />
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-airplay text-light" style="font-size:80px;"></i>
                </div>
                <h5 class="fw-bold">No Flash Modals Found</h5>
                <p class="text-muted mb-0">Create your first flash modal to show marketing popups on the homepage.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <x-modern.pagination :collection="$flashModals" />
</x-modern.card>
@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have permission to view flash modals.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection

@push('scripts')
<script>
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Delete Flash Modal?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@endpush
