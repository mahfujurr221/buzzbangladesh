@extends('backend.layouts.master')

@section('title', 'Testimonials')

@push('css')
<style>
    .star-rating { color: #f59e0b; }
</style>
@endpush

@section('content')

@can('list-testimonial')
<x-modern.filter title="Filter Testimonials" icon="bx bx-search-alt" :resetUrl="route('testimonials.index')"
    :expanded="request()->anyFilled(['search'])">
    <div class="col-md-6">
        <x-modern.input label="Search Keyword" name="search" placeholder="Search by name or title..." :value="request('search')"
            icon="bx bx-search" />
    </div>
</x-modern.filter>

<x-modern.card title="Testimonials" icon="bx bx-message-square">
    <x-slot name="actions">
        @can('create-testimonial')
        <x-modern.actions.button type="button" actionType="add" label="Add Testimonial" size="sm"
            data-bs-toggle="modal" data-bs-target="#createTestimonialModal" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Name', 'Comment', 'Rating', 'Status', 'Actions']">
        @forelse($testimonials as $testimonial)
        <tr>
            <td class="align-middle text-center">{{ $loop->iteration + ($testimonials->currentPage() - 1) * $testimonials->perPage() }}</td>
            <td class="align-middle">
                <div class="fw-bold text-dark">{{ $testimonial->name }}</div>
                @if($testimonial->title)
                    <div class="small text-muted">{{ $testimonial->title }}</div>
                @endif
            </td>
            <td class="align-middle" style="max-width: 300px;">
                <div class="text-truncate" title="{{ $testimonial->comment }}">{{ $testimonial->comment }}</div>
            </td>
            <td class="align-middle text-center">
                <div class="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $testimonial->rating)
                            <i class="bx bxs-star"></i>
                        @else
                            <i class="bx bx-star"></i>
                        @endif
                    @endfor
                </div>
            </td>
            <td class="align-middle text-center">
                <div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input toggle-status" type="checkbox" data-id="{{ $testimonial->id }}" {{ $testimonial->active_status ? 'checked' : '' }} style="cursor: pointer;">
                </div>
            </td>
            <td class="align-middle text-center">
                <div class="d-flex gap-2 justify-content-center">
                    @can('edit-testimonial')
                    <x-modern.actions.button type="button" actionType="edit" outline
                        data-bs-toggle="modal"
                        data-bs-target="#editTestimonialModal"
                        data-id="{{ $testimonial->id }}"
                        data-name="{{ $testimonial->name }}"
                        data-title="{{ $testimonial->title }}"
                        data-comment="{{ $testimonial->comment }}"
                        data-rating="{{ $testimonial->rating }}"
                        data-active="{{ $testimonial->active_status ? '1' : '0' }}"
                    />
                    @endcan
                    @can('delete-testimonial')
                    <form action="{{ route('testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button type="button" actionType="delete" outline class="delete-testimonial-btn" />
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-message-x text-light" style="font-size:80px;"></i>
                </div>
                <h5 class="fw-bold">No Testimonials Found</h5>
                <p class="text-muted mb-0">Create your first testimonial to display on the frontend.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <x-modern.pagination :collection="$testimonials" />
</x-modern.card>

@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have permission to view the testimonials list.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

{{-- ────────────────── Create Testimonial Modal ────────────────── --}}
<x-modern.modal id="createTestimonialModal" title="Add New Testimonial">
    <form action="{{ route('testimonials.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Title/Designation</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Comment <span class="text-danger">*</span></label>
            <textarea name="comment" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Rating <span class="text-danger">*</span></label>
            <select name="rating" class="form-select" required>
                <option value="5" selected>5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
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

{{-- ────────────────── Edit Testimonial Modal ────────────────── --}}
<x-modern.modal id="editTestimonialModal" title="Edit Testimonial">
    <form id="editTestimonialForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Title/Designation</label>
            <input type="text" name="title" id="edit_title" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Comment <span class="text-danger">*</span></label>
            <textarea name="comment" id="edit_comment" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Rating <span class="text-danger">*</span></label>
            <select name="rating" id="edit_rating" class="form-select" required>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
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
    $('#editTestimonialModal').on('show.bs.modal', function(event) {
        const btn = $(event.relatedTarget);
        const form = $('#editTestimonialForm');

        form.attr('action', '/back/testimonials/' + btn.data('id'));
        $('#edit_name').val(btn.data('name'));
        $('#edit_title').val(btn.data('title'));
        $('#edit_comment').val(btn.data('comment'));
        $('#edit_rating').val(btn.data('rating'));
        $('#edit_active_status').prop('checked', btn.data('active') == 1);
    });

    $(document).on('click', '.delete-testimonial-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Delete Testimonial?',
            text: 'This will permanently remove the testimonial.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    $('.toggle-status').on('change', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `/back/testimonials/${id}/toggle-status`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.status === 'success') {
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Failed to update status!');
                $(this).prop('checked', !$(this).prop('checked'));
            }.bind(this)
        });
    });
</script>
@endpush
