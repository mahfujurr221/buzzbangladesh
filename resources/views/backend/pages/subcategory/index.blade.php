@extends('backend.layouts.master')

@section('title', 'Subcategories')

@section('content')

<x-modern.card title="Subcategory List" icon="bx bx-list-ul">
    <x-slot name="actions">
        <x-modern.actions.button data-bs-toggle="modal" data-bs-target="#addSubcategoryModal" actionType="add" label="Add New" size="sm" />
    </x-slot>

    <x-modern.table :headers="['#', 'Logo', 'Subcategory Name', 'Parent Category', 'Status', 'Actions']">
        @forelse ($subcategories as $key => $data)
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
            <td class="align-middle">
                <span class="text-secondary">{{ $data->category->name ?? 'N/A' }}</span>
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
                        data-bs-target="#editSubcategoryModal" data-id="{{ $data->id }}" data-name="{{ $data->name }}" data-category-id="{{ $data->category_id }}" data-status="{{ $data->active_status }}" data-logo="{{ $data->logo }}"
                        actionType="edit" outline size="sm" />

                    <form action="{{ route('subcategories.destroy', $data->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button actionType="delete" type="submit" size="sm"
                            onclick="return confirm('Are you sure you want to delete this subcategory?')" outline />
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-list-x text-light" style="font-size: 80px;"></i>
                </div>
                <h5 class="fw-bold">No Subcategories Found</h5>
                <p class="text-muted mb-0">Create a new subcategory to get started.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>
</x-modern.card>

{{-- Add Modal --}}
<x-modern.modal id="addSubcategoryModal" title="Add New Subcategory" icon="bx bx-plus" variant="primary">
    <form action="{{ route('subcategories.store') }}" method="POST" enctype="multipart/form-data" id="addSubcategoryForm">
        @csrf
        <div class="mb-3">
            <label class="form-label">Parent Category <span class="text-danger">*</span></label>
            <select class="form-select" name="category_id" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <x-modern.input label="Subcategory Name" name="name" placeholder="Enter Subcategory Name" required icon="bx bx-list-ul" />
        </div>
        <div class="mb-3">
            <x-modern.input type="file" label="Subcategory Logo" name="logo" id="add_logo_input" icon="bx bx-image" />
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
            <x-modern.actions.button actionType="save" type="submit" form="addSubcategoryForm" />
        </x-slot>
    </form>
</x-modern.modal>

{{-- Edit Modal --}}
<x-modern.modal id="editSubcategoryModal" title="Update Subcategory" icon="bx bx-pencil" variant="info">
    <form method="POST" enctype="multipart/form-data" id="editSubcategoryForm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Parent Category <span class="text-danger">*</span></label>
            <select class="form-select" name="category_id" id="edit_category_id" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <x-modern.input label="Subcategory Name" name="name" id="edit_name" placeholder="Enter Subcategory Name" required
                icon="bx bx-list-ul" />
        </div>
        <div class="mb-3">
            <x-modern.input type="file" label="Subcategory Logo (Leave empty to keep current)" name="logo" id="edit_logo_input" icon="bx bx-image" />
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
            <x-modern.actions.button actionType="update" type="submit" form="editSubcategoryForm" />
        </x-slot>
    </form>
</x-modern.modal>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.editButton', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var category_id = $(this).data('category-id');
        var status = $(this).data('status');

        var url= "{{ route('subcategories.update', ':id') }}";
        url = url.replace(':id', id);

        $('#editSubcategoryModal form').attr('action', url);
        $('#edit_name').val(name);
        $('#edit_category_id').val(category_id);
        
        if (status == 1) {
            $('#edit_active_status').prop('checked', true);
        } else {
            $('#edit_active_status').prop('checked', false);
        }

        var logo = $(this).data('logo');
        if(logo) {
            $('#edit_logo_preview').attr('src', '/backend/images/' + logo);
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
