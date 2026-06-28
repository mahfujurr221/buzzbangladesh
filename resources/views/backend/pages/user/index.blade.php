@extends('backend.layouts.master')

@section('title', 'Admin Users')

@section('content')

@can('list-user')
<x-modern.filter title="Filter Admin Users" icon="bx bx-search-alt" :resetUrl="route('users.index')"
    :expanded="request()->anyFilled(['name', 'phone', 'role'])">
    <div class="col-md-4">
        <x-modern.input label="Full Name" name="name" placeholder="Search by name" :value="request('name')"
            icon="bx bx-user" />
    </div>
    <div class="col-md-4">
        <x-modern.input label="Phone Number" name="phone" placeholder="Search by phone" :value="request('phone')"
            icon="bx bx-phone" />
    </div>
    <div class="col-md-4">
        <x-modern.select label="User Role" name="role" :options="$roles->pluck('name', 'name')" placeholder="All Roles"
            :selected="request('role')" icon="bx bx-shield-quarter" />
    </div>
</x-modern.filter>

<x-modern.card title="Admin Users" icon="bx bx-group">
    <x-slot name="actions">
        @can('create-user')
        <x-modern.actions.button tag="a" href="{{ route('users.create') }}" actionType="add" label="Add New" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Profile', 'Contact', 'Role', 'Status', 'Created', 'Actions']">
        @forelse($users as $user)
        <tr>
            <td class="align-middle">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
            <td class="align-middle">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="avatar-sm me-3">
                        <img src="{{ $user->profilePhoto() }}" alt="" class="rounded-circle img-thumbnail">
                    </div>
                    <div>
                        <h6 class="mb-0 text-dark fw-bold">{{ $user->fullName() ?? 'N/A' }}</h6>
                        <small class="text-muted d-block">ID: #{{ $user->id }}</small>
                    </div>
                </div>
            </td>
            <td class="align-middle">
                <div class="d-flex flex-column">
                    <span class="text-dark"><i class="bx bx-envelope me-1 text-muted"></i>{{ $user->email }}</span>
                    <span class="text-muted small"><i class="bx bx-phone me-1 text-muted"></i>{{ $user->phone ?? 'N/A'
                        }}</span>
                </div>
            </td>
            <td class="align-middle">
                @foreach($user->roles as $role)
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"
                    style="border-radius: 6px;">{{ $role->name }}</span>
                @endforeach
            </td>
            <td class="align-middle">
                @if($user->adminProfile && $user->adminProfile->active_flag)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1"
                    style="border-radius: 20px;">
                    <i class="bx bxs-circle font-size-8 me-1"></i>Active
                </span>
                @else
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1"
                    style="border-radius: 20px;">
                    <i class="bx bxs-circle font-size-8 me-1"></i>Inactive
                </span>
                @endif
            </td>
            <td class="align-middle text-muted">
                {{ $user->created_at->format('d M, Y') }}
            </td>
            <td class="align-middle">
                <div class="d-flex justify-content-center gap-2">
                    @can('edit-user')
                    <x-modern.actions.button tag="a" href="{{ route('users.edit', $user->id) }}" actionType="edit"
                        outline />
                    @endcan

                    @can('delete-user')
                    @if($user->id !== Auth::id())
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button actionType="delete" type="submit"
                            onclick="return confirm('Are you sure you want to delete this admin user?')" outline />
                    </form>
                    @endif
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-user-x text-light" style="font-size: 80px;"></i>
                </div>
                <h5 class="fw-bold">No Admin Users Found</h5>
                <p class="text-muted mb-0">Try adjusting your filters or create a new admin user.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <x-modern.pagination :collection="$users" />
</x-modern.card>
@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to view the user list.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light"
            icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection