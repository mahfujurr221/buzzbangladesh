@extends('backend.layouts.master')

@section('title', 'Product Colors')

@section('content')

<x-modern.card title="Product Color List" icon="bx bx-aperture">
    <x-slot name="actions">
        <x-modern.actions.button data-bs-toggle="modal" data-bs-target="#addColorModal" actionType="add" label="Add New" size="sm" />
    </x-slot>

    <x-modern.table :headers="['#', 'Color Name', 'Code', 'Status', 'Actions']">
        @forelse ($colors as $key => $data)
        <tr>
            <td class="align-middle text-center">{{ $key + 1 }}</td>
            <td class="align-middle">
                <span class="fw-bold text-dark">{{ $data->name }}</span>
            </td>
            <td class="align-middle">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $data->code }}; border: 1px solid #ddd;"></div>
                    <span>{{ $data->code }}</span>
                </div>
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
                        data-bs-target="#editColorModal" data-id="{{ $data->id }}" data-name="{{ $data->name }}" data-code="{{ $data->code }}" data-status="{{ $data->active_status }}"
                        actionType="edit" outline size="sm" />

                    <form action="{{ route('colors.destroy', $data->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button actionType="delete" type="submit" size="sm"
                            onclick="return confirm('Are you sure you want to delete this color?')" outline />
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-aperture text-light" style="font-size: 80px;"></i>
                </div>
                <h5 class="fw-bold">No Colors Found</h5>
                <p class="text-muted mb-0">Create a new color to get started.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>
</x-modern.card>

{{-- Add Modal --}}
<x-modern.modal id="addColorModal" title="Add New Color" icon="bx bx-plus" variant="primary">
    <form action="{{ route('colors.store') }}" method="POST" id="addColorForm">
        @csrf
        <div class="mb-3">
            <x-modern.input label="Color Name" name="name" placeholder="Enter Color (e.g. Red, Dark Blue)" required icon="bx bx-aperture" />
        </div>
        <div class="mb-3">
            <label class="form-label">Color Picker <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="color" id="addColorPicker" class="form-control form-control-color" value="#ff0000" title="Choose your color" style="max-width: 60px; padding: 0.375rem;">
                <input type="text" name="code" id="addCodeInput" class="form-control" value="#ff0000" required placeholder="e.g. #ff0000" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
            </div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="active_status" name="active_status" value="1" checked>
            <label class="form-check-label" for="active_status">Active Status</label>
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" />
            <x-modern.actions.button actionType="save" type="submit" form="addColorForm" />
        </x-slot>
    </form>
</x-modern.modal>

{{-- Edit Modal --}}
<x-modern.modal id="editColorModal" title="Update Color" icon="bx bx-pencil" variant="info">
    <form method="POST" id="editColorForm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <x-modern.input label="Color Name" name="name" id="edit_name" placeholder="Enter Color Name" required
                icon="bx bx-aperture" />
        </div>
        <div class="mb-3">
            <label class="form-label">Color Picker <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="color" id="editColorPicker" class="form-control form-control-color" title="Choose your color" style="max-width: 60px; padding: 0.375rem;">
                <input type="text" name="code" id="edit_code" class="form-control" required placeholder="e.g. #ff0000" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
            </div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="edit_active_status" name="active_status" value="1">
            <label class="form-check-label" for="edit_active_status">Active Status</label>
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" />
            <x-modern.actions.button actionType="update" type="submit" form="editColorForm" />
        </x-slot>
    </form>
</x-modern.modal>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.editButton', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var code = $(this).data('code');
        var status = $(this).data('status');

        var url= "{{ route('colors.update', ':id') }}";
        url = url.replace(':id', id);

        $('#editColorModal form').attr('action', url);
        $('#edit_name').val(name);
        $('#edit_code').val(code);
        $('#editColorPicker').val(code);
        
        if (status == 1) {
            $('#edit_active_status').prop('checked', true);
        } else {
            $('#edit_active_status').prop('checked', false);
        }
    });

    // Sync Add Color Picker
    $('#addColorPicker').on('input', function() {
        $('#addCodeInput').val($(this).val());
    });
    
    $('#addCodeInput').on('input', function() {
        var val = $(this).val();
        if(val.length === 7 && val.startsWith('#')) {
            $('#addColorPicker').val(val);
        }
    });

    // Sync Edit Color Picker
    $('#editColorPicker').on('input', function() {
        $('#edit_code').val($(this).val());
    });
    
    $('#edit_code').on('input', function() {
        var val = $(this).val();
        if(val.length === 7 && val.startsWith('#')) {
            $('#editColorPicker').val(val);
        }
    });
</script>
@endpush
