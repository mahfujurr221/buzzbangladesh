@extends('backend.layouts.master')

@section('title', 'User Roles')

@section('content')

<x-modern.card title="Role List" icon="bx bx-shield">
    <x-slot name="actions">
        <x-modern.actions.button data-bs-toggle="modal" data-bs-target="#addRoleModal" actionType="add" label="Add New" size="sm" />
    </x-slot>

    <x-modern.table :headers="['#', 'Role Name', 'Actions']">
        @forelse ($roles as $key => $data)
        <tr>
            <td class="align-middle text-center">{{ $key + 1 }}</td>
            <td class="align-middle">
                <span class="fw-bold text-dark">{{ $data->name }}</span>
            </td>
            <td class="align-middle text-center">
                <div class="d-flex justify-content-center gap-2">
                    <x-modern.actions.button tag="a" href="{{ route('role.permissions', $data->id) }}"
                        label="Permissions" icon="bx bx-list-check" variant="success" outline size="sm" />

                    <x-modern.actions.button tag="button" type="button" class="editButton" data-bs-toggle="modal"
                        data-bs-target="#editRoleModal" data-id="{{ $data->id }}" data-name="{{ $data->name }}"
                        actionType="edit" outline size="sm" />

                    <form action="{{ route('roles.destroy', $data->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button actionType="delete" type="submit" size="sm"
                            onclick="return confirm('Are you sure you want to delete this role?')" outline />
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
                <h5 class="fw-bold">No Roles Found</h5>
                <p class="text-muted mb-0">Create a new role to get started.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>
</x-modern.card>

{{-- Add Modal --}}
<x-modern.modal id="addRoleModal" title="Add New Role" icon="bx bx-plus" variant="primary">
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <x-modern.input label="Role Name" name="name" placeholder="Enter Role Name" required icon="bx bx-shield" />
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" />
            <x-modern.actions.button actionType="save" type="submit" />
        </x-slot>
    </form>
</x-modern.modal>

{{-- Edit Modal --}}
<x-modern.modal id="editRoleModal" title="Update Role" icon="bx bx-pencil" variant="info">
    <form method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <x-modern.input label="Role Name" name="name" id="edit_name" placeholder="Enter Role Name" required
                icon="bx bx-shield" />
        </div>
        <x-slot name="footerActions">
            <x-modern.actions.button actionType="cancel" data-bs-dismiss="modal" />
            <x-modern.actions.button actionType="update" type="submit" />
        </x-slot>
    </form>
</x-modern.modal>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.editButton', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');

        var url= "{{ route('roles.update', ':id') }}";
        url = url.replace(':id', id);

        $('#editRoleModal form').attr('action', url);
        $('#edit_name').val(name);
    });
</script>
@endpush