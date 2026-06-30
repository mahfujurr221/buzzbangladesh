@extends('backend.layouts.master')

@section('title', 'Product Sizes')

@section('content')

<x-modern.card title="Product Size List" icon="bx bx-maximize">
    <x-slot name="actions">
        <x-modern.actions.button data-bs-toggle="modal" data-bs-target="#addSizeModal" actionType="add" label="Add New" size="sm" />
    </x-slot>

    <x-modern.table :headers="['#', 'Size Name', 'Body Size', 'Height', 'Status', 'Actions']">
        @forelse ($sizes as $key => $data)
        <tr>
            <td class="align-middle text-center">{{ $key + 1 }}</td>
            <td class="align-middle">
                <span class="fw-bold text-dark">{{ $data->name }}</span>
            </td>
            <td class="align-middle">
                <span class="text-secondary">{{ $data->body_size ?? 'N/A' }}</span>
            </td>
            <td class="align-middle">
                <span class="text-secondary">{{ $data->height ?? 'N/A' }}</span>
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
                        data-bs-target="#editSizeModal" data-id="{{ $data->id }}" data-name="{{ $data->name }}" data-body-size="{{ $data->body_size }}" data-height="{{ $data->height }}" data-status="{{ $data->active_status }}"
                        actionType="edit" outline size="sm" />

                    <form action="{{ route('sizes.destroy', $data->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button actionType="delete" type="submit" size="sm"
                            onclick="return confirm('Are you sure you want to delete this size?')" outline />
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-maximize text-light" style="font-size: 80px;"></i>
                </div>
                <h5 class="fw-bold">No Sizes Found</h5>
                <p class="text-muted mb-0">Create a new size to get started.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>
</x-modern.card>

{{-- Add Modal --}}
<x-modern.modal id="addSizeModal" title="Add New Size" icon="bx bx-plus" variant="primary">
    <form action="{{ route('sizes.store') }}" method="POST" id="addSizeForm">
        @csrf
        <div class="mb-3">
            <x-modern.input label="Size Name" name="name" placeholder="Enter Size (e.g. XL, 42)" required icon="bx bx-maximize" />
        </div>
        <div class="mb-3">
            <x-modern.input label="Body Size / Measurement" name="body_size" placeholder="e.g. Bust: 34, Waist: 28, Hips: 38" icon="bx bx-ruler" />
        </div>
        <div class="mb-3">
            <x-modern.input label="Height" name="height" placeholder="e.g. 5'5&quot; - 5'7&quot;" icon="bx bx-body" />
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="active_status" name="active_status" value="1" checked>
            <label class="form-check-label" for="active_status">Active Status</label>
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" />
            <x-modern.actions.button actionType="save" type="submit" form="addSizeForm" />
        </x-slot>
    </form>
</x-modern.modal>

{{-- Edit Modal --}}
<x-modern.modal id="editSizeModal" title="Update Size" icon="bx bx-pencil" variant="info">
    <form method="POST" id="editSizeForm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <x-modern.input label="Size Name" name="name" id="edit_name" placeholder="Enter Size Name" required
                icon="bx bx-maximize" />
        </div>
        <div class="mb-3">
            <x-modern.input label="Body Size / Measurement" name="body_size" id="edit_body_size" placeholder="e.g. Bust: 34, Waist: 28, Hips: 38" icon="bx bx-ruler" />
        </div>
        <div class="mb-3">
            <x-modern.input label="Height" name="height" id="edit_height" placeholder="e.g. 5'5&quot; - 5'7&quot;" icon="bx bx-body" />
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="edit_active_status" name="active_status" value="1">
            <label class="form-check-label" for="edit_active_status">Active Status</label>
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" />
            <x-modern.actions.button actionType="update" type="submit" form="editSizeForm" />
        </x-slot>
    </form>
</x-modern.modal>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.editButton', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var body_size = $(this).data('body-size');
        var height = $(this).data('height');
        var status = $(this).data('status');

        var url= "{{ route('sizes.update', ':id') }}";
        url = url.replace(':id', id);

        $('#editSizeModal form').attr('action', url);
        $('#edit_name').val(name);
        $('#edit_body_size').val(body_size);
        $('#edit_height').val(height);
        
        if (status == 1) {
            $('#edit_active_status').prop('checked', true);
        } else {
            $('#edit_active_status').prop('checked', false);
        }
    });
</script>
@endpush
