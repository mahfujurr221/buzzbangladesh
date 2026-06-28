@extends('backend.layouts.master')

@section('title', 'Assign Permissions')

@section('content')

<x-modern.card title="Role: {{ $role->name }}" icon="bx bx-shield-quarter">
    <x-slot name="actions">
        <div class="d-flex gap-2">
            <x-modern.actions.button tag="button" type="button" id="checkAll" label="Select All" icon="bx bx-check-double"
                variant="success" outline size="sm" />
            <x-modern.actions.button tag="button" type="button" id="uncheckAll" label="Unselect All" icon="bx bx-x"
                variant="danger" outline size="sm" />
            <x-modern.actions.button tag="a" href="{{ route('roles.index') }}" actionType="back" size="sm" />
        </div>
    </x-slot>

    <form action="{{ route('role-permissions.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            @foreach ($groupedPermissions as $group => $permissions)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 border shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-light border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="bx bx-folder-open me-2"></i>{{ ucfirst($group) }}
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-2">
                            @foreach ($permissions as $permission)
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input" type="checkbox" name="permission[]"
                                    value="{{ $permission->name }}" id="perm_{{ $permission->id }}" {{
                                    $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="perm_{{ $permission->id }}" style="cursor: pointer;">
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5 text-center border-top pt-4">
            <x-modern.actions.button actionType="update" type="submit" label="Update Permissions" />
        </div>
    </form>
</x-modern.card>

<style>
    .custom-checkbox .form-check-input:checked {
        background-color: #556ee6;
        border-color: #556ee6;
    }
    .custom-checkbox .form-check-label {
        font-size: 0.9rem;
        transition: color 0.2s;
    }
    .custom-checkbox:hover .form-check-label {
        color: #556ee6 !important;
    }
</style>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('checkAll').addEventListener('click', function () {
            document.querySelectorAll('input[name="permission[]"]').forEach(checkbox => checkbox.checked = true);
        });

        document.getElementById('uncheckAll').addEventListener('click', function () {
            document.querySelectorAll('input[name="permission[]"]').forEach(checkbox => checkbox.checked = false);
        });
    });
</script>
@endpush