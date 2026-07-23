@extends('backend.layouts.master')

@section('title', 'Categories')

@section('content')

<x-modern.card title="Category List" icon="bx bx-grid">
    <x-slot name="actions">
        <x-modern.actions.button data-bs-toggle="modal" data-bs-target="#addCategoryModal" actionType="add" label="Add New" size="sm" />
    </x-slot>

    <x-modern.table :headers="['#', 'Logo', 'Category Name', 'Status', 'Actions']">
        @forelse ($categories as $key => $data)
        <tr>
            <td class="align-middle text-center">{{ $key + 1 }}</td>
            <td class="align-middle text-center">
                @if($data->logo)
                    <img src="{{ asset('backend/images/' . $data->logo) }}" alt="{{ $data->name }}" class="img-thumbnail rounded-circle p-0" style="width: 45px; height: 45px; object-fit: cover;">
                @else
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted mx-auto" style="width: 45px; height: 45px;">
                        <i class="bx bx-image fs-4"></i>
                    </div>
                @endif
            </td>
            <td class="align-middle">
                <span class="fw-bold text-dark">{{ $data->name }}</span>
            </td>
            <td class="align-middle text-center">
                @if($data->active_status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td class="align-middle text-center">
                <div class="d-flex justify-content-center gap-2">
                    <x-modern.actions.button tag="button" type="button" class="editButton" data-bs-toggle="modal"
                        data-bs-target="#editCategoryModal" data-id="{{ $data->id }}" data-name="{{ $data->name }}" data-status="{{ $data->active_status }}" data-logo="{{ $data->logo }}"
                        actionType="edit" outline size="sm" />

                    <form action="{{ route('categories.destroy', $data->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button actionType="delete" type="submit" size="sm"
                            onclick="return confirm('Are you sure you want to delete this category?')" outline />
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-grid-x text-light" style="font-size: 80px;"></i>
                </div>
                <h5 class="fw-bold">No Categories Found</h5>
                <p class="text-muted mb-0">Create a new category to get started.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>
</x-modern.card>

{{-- Add Modal --}}
<form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" id="addCategoryForm">
<x-modern.modal id="addCategoryModal" title="Add New Category" icon="bx bx-plus" variant="primary">
        @csrf
        <div class="mb-3">
            <x-modern.input label="Category Name" name="name" placeholder="Enter Category Name" required icon="bx bx-grid" />
        </div>
        <div class="mb-3">
            <x-modern.input type="file" label="Category Logo" name="logo" id="add_logo_input" icon="bx bx-image" />
            <div class="mt-2 text-center" style="display: none;" id="add_logo_preview_container">
                <img id="add_logo_preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
            </div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="active_status" name="active_status" value="1" checked>
            <label class="form-check-label" for="active_status">Active Status</label>
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" />
            <x-modern.actions.button actionType="save" type="submit" />
        </x-slot>
</x-modern.modal>
</form>

{{-- Edit Modal --}}
<form method="POST" enctype="multipart/form-data" id="editCategoryForm">
<x-modern.modal id="editCategoryModal" title="Update Category" icon="bx bx-pencil" variant="info">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <x-modern.input label="Category Name" name="name" id="edit_name" placeholder="Enter Category Name" required
                icon="bx bx-grid" />
        </div>
        <div class="mb-3">
            <x-modern.input type="file" label="Category Logo (Leave empty to keep current)" name="logo" id="edit_logo_input" icon="bx bx-image" />
            <div class="mt-2 text-center" style="display: none;" id="edit_logo_preview_container">
                <img id="edit_logo_preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
            </div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="edit_active_status" name="active_status" value="1">
            <label class="form-check-label" for="edit_active_status">Active Status</label>
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" />
            <x-modern.actions.button actionType="update" type="submit" />
        </x-slot>
</x-modern.modal>
</form>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.editButton', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');

        var url= "{{ route('categories.update', ':id') }}";
        url = url.replace(':id', id);

        $('#editCategoryForm').attr('action', url);
        $('#edit_name').val(name);
        
        if (status == 1) {
            $('#edit_active_status').prop('checked', true);
        } else {
            $('#edit_active_status').prop('checked', false);
        }

        var logo = $(this).data('logo');
        if(logo) {
            var basePath = "{{ asset('backend/images') }}";
            $('#edit_logo_preview').attr('src', basePath + '/' + logo);
            $('#edit_logo_preview_container').show();
        } else {
            $('#edit_logo_preview').attr('src', '');
            $('#edit_logo_preview_container').hide();
        }
    });

    $('#add_logo_input').change(function() {
        previewImage(this, 'add_logo_preview');
    });
    
    $('#edit_logo_input').change(function() {
        previewImage(this, 'edit_logo_preview');
    });

    function previewImage(input, previewId) {
        var preview = document.getElementById(previewId);
        var container = document.getElementById(previewId + '_container');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = "";
            container.style.display = 'none';
        }
    }
</script>
@endpush
