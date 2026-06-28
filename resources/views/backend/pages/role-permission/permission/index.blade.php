@extends('backend.layouts.master')

@section('title', 'Permissions')

@section('content')

<x-modern.filter title="Filter Permissions" icon="bx bx-search-alt" :resetUrl="route('permissions.index')"
    :expanded="request()->anyFilled(['name'])">
    <div class="col-md-6">
        <x-modern.input label="Permission Name" name="name" placeholder="Search by name" :value="request('name')"
            icon="bx bx-key" />
    </div>
</x-modern.filter>

<x-modern.card title="Permission List" icon="bx bx-shield-quarter">
    <x-slot name="actions">
        <x-modern.actions.button data-bs-toggle="modal" data-bs-target="#addPermissionModal" actionType="add"
            label="Add New" size="sm" />
    </x-slot>

    <x-modern.table :headers="['#', 'Permission Name', 'Actions']">
        @forelse ($permissions as $key => $data)
        <tr>
            <td class="align-middle text-center">{{ $key + 1 + ($permissions->currentPage() - 1) * $permissions->perPage() }}</td>
            <td class="align-middle">
                <span class="fw-bold text-dark">{{ $data->name }}</span>
            </td>
            <td class="align-middle text-center">
                <div class="d-flex justify-content-center gap-2">
                    <x-modern.actions.button tag="button" type="button" class="editButton" data-bs-toggle="modal"
                        data-bs-target="#editPermissionModal" data-id="{{ $data->id }}" data-name="{{ $data->name }}"
                        actionType="edit" outline />

                    <form action="{{ route('permissions.destroy', $data->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button actionType="delete" type="submit"
                            onclick="return confirm('Are you sure you want to delete this permission?')" outline />
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-shield-x text-light" style="font-size: 80px;"></i>
                </div>
                <h5 class="fw-bold">No Permissions Found</h5>
                <p class="text-muted mb-0">Try adjusting your filters or create a new permission.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <x-modern.pagination :collection="$permissions" />
</x-modern.card>

{{-- Add Permission Modal --}}
<x-modern.modal id="addPermissionModal" title="Add New Permission" icon="bx bx-plus" variant="primary">
    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <x-modern.input label="Permission Name" name="name" placeholder="Enter Permission Name" required
                icon="bx bx-key" />
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" size="sm" />
            <x-modern.actions.button actionType="save" type="submit" />
        </x-slot>
    </form>
</x-modern.modal>

{{-- Edit Permission Modal --}}
<x-modern.modal id="editPermissionModal" title="Update Permission" icon="bx bx-pencil" variant="info">
    <form method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <x-modern.input label="Permission Name" name="name" id="edit_name" placeholder="Enter Permission Name"
                required icon="bx bx-key" />
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" size="sm" />
            <x-modern.actions.button actionType="update" type="submit" />
        </x-slot>
    </form>
</x-modern.modal>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.editButton');
        const editModal = document.getElementById('editPermissionModal');
        const editNameInput = document.getElementById('edit_name');
        const form = editModal.querySelector('form');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const permissionId = button.getAttribute('data-id');
                const permissionName = button.getAttribute('data-name');

                form.action = `/permissions/${permissionId}`;
                editNameInput.value = permissionName;
            });
        });

        // Show edit modal if validation fails
        @if ($errors->any() && old('edit_permission_id'))
            new bootstrap.Modal(editModal).show();
        @endif
    });
</script>
@endpush