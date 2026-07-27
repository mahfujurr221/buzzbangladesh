@extends('backend.layouts.master')

@section('title', 'Seasons')

@push('css')
<style>
    .season-status-badge {
        font-size: 11px;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .date-range-display {
        font-size: 12px;
        color: #8592a3;
    }
</style>
@endpush

@section('content')

@can('list-season')
<x-modern.filter title="Filter Seasons" icon="bx bx-search-alt" :resetUrl="route('seasons.index')"
    :expanded="request()->anyFilled(['search'])">
    <div class="col-md-6">
        <x-modern.input label="Search Keyword" name="search" placeholder="Search by season name..." :value="request('search')"
            icon="bx bx-search" />
    </div>
</x-modern.filter>

<x-modern.card title="Seasons" icon="bx bx-calendar-star">
    <x-slot name="actions">
        @can('create-season')
        <x-modern.actions.button type="button" actionType="add" label="Add Season" size="sm"
            data-bs-toggle="modal" data-bs-target="#createSeasonModal" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Season Name', 'Date Range', 'Products', 'Status', 'Actions']">
        @forelse($seasons as $season)
        <tr>
            <td class="align-middle text-center">{{ $loop->iteration + ($seasons->currentPage() - 1) * $seasons->perPage() }}</td>
            <td class="align-middle">
                <div class="fw-bold text-dark">{{ $season->name }}</div>
                @if($season->description)
                    <div class="small text-muted">{{ Str::limit($season->description, 60) }}</div>
                @endif
            </td>
            <td class="align-middle">
                @if($season->start_date || $season->end_date)
                    <div class="date-range-display">
                        <i class="bx bx-calendar me-1"></i>
                        {{ $season->start_date ? $season->start_date->format('d M Y') : '∞' }}
                        &nbsp;→&nbsp;
                        {{ $season->end_date ? $season->end_date->format('d M Y') : '∞' }}
                    </div>
                @else
                    <span class="text-muted small">No date range</span>
                @endif
            </td>
            <td class="align-middle text-center">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="border-radius: 20px;">
                    {{ $season->products_count }} product{{ $season->products_count !== 1 ? 's' : '' }}
                </span>
            </td>
            <td class="align-middle text-center">
                <div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input toggle-status" type="checkbox" data-id="{{ $season->id }}" {{ $season->active_status ? 'checked' : '' }} style="cursor: pointer;">
                </div>
                <div class="small mt-1 text-muted" id="status-label-{{ $season->id }}">{{ ucfirst($season->status_label) }}</div>
            </td>
            <td class="align-middle text-center">
                <div class="d-flex gap-2 justify-content-center">
                    @can('edit-season')
                    <x-modern.actions.button type="button" actionType="edit" outline
                        data-bs-toggle="modal"
                        data-bs-target="#editSeasonModal"
                        data-id="{{ $season->id }}"
                        data-name="{{ $season->name }}"
                        data-description="{{ $season->description }}"
                        data-start="{{ $season->start_date?->format('Y-m-d') }}"
                        data-end="{{ $season->end_date?->format('Y-m-d') }}"
                        data-active="{{ $season->active_status ? '1' : '0' }}"
                    />
                    @endcan
                    @can('delete-season')
                    <form action="{{ route('seasons.destroy', $season->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button type="button" actionType="delete" outline class="delete-season-btn" />
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-calendar-x text-light" style="font-size:80px;"></i>
                </div>
                <h5 class="fw-bold">No Seasons Found</h5>
                <p class="text-muted mb-0">Create your first season to organise products by collection periods.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <x-modern.pagination :collection="$seasons" />
</x-modern.card>

@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have permission to view the seasons list.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

{{-- ────────────────── Create Season Modal ────────────────── --}}
<x-modern.modal id="createSeasonModal" title="Add New Season">
    <form action="{{ route('seasons.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Season Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required placeholder="e.g. Eid-ul-Fitr 2026">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Optional notes about this season..."></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control">
            </div>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="active_status" value="1" id="create_active_status" checked>
            <label class="form-check-label" for="create_active_status">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <x-modern.actions.button type="button" actionType="cancel" outline data-bs-dismiss="modal" />
            <x-modern.actions.button type="submit" actionType="save" />
        </div>
    </form>
</x-modern.modal>

{{-- ────────────────── Edit Season Modal ────────────────── --}}
<x-modern.modal id="editSeasonModal" title="Edit Season">
    <form id="editSeasonForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Season Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_season_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" id="edit_season_description" class="form-control" rows="2"></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" id="edit_season_start" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" id="edit_season_end" class="form-control">
            </div>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="active_status" value="1" id="edit_active_status">
            <label class="form-check-label" for="edit_active_status">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <x-modern.actions.button type="button" actionType="cancel" outline data-bs-dismiss="modal" />
            <x-modern.actions.button type="submit" actionType="save" />
        </div>
    </form>
</x-modern.modal>

@endsection

@push('scripts')
<script>
    // Populate edit modal
    $('#editSeasonModal').on('show.bs.modal', function(event) {
        const btn = $(event.relatedTarget);
        const form = $('#editSeasonForm');

        form.attr('action', '/back/seasons/' + btn.data('id'));
        $('#edit_season_name').val(btn.data('name'));
        $('#edit_season_description').val(btn.data('description'));
        $('#edit_season_start').val(btn.data('start'));
        $('#edit_season_end').val(btn.data('end'));
        $('#edit_active_status').prop('checked', btn.data('active') == 1);
    });

    // Delete confirmation
    $(document).on('click', '.delete-season-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Delete Season?',
            text: 'This will delete the season. Products assigned to it will have no season.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Toggle status
    $('.toggle-status').on('change', function() {
        const id = $(this).data('id');
        const isChecked = $(this).is(':checked');
        
        $.ajax({
            url: `/back/seasons/${id}/toggle-status`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.status === 'success') {
                    toastr.success(response.message);
                    
                    // Update the label text below the toggle
                    let labelText = response.status_label;
                    $('#status-label-' + id).text(labelText.charAt(0).toUpperCase() + labelText.slice(1));
                }
            },
            error: function(xhr) {
                toastr.error('Failed to update status!');
                // Revert the toggle state on error
                $(`.toggle-status[data-id="${id}"]`).prop('checked', !isChecked);
            }
        });
    });
</script>
@endpush
