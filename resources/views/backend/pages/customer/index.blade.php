@extends('backend.layouts.master')

@section('title', 'Customers Management')

@section('content')

@can('list-customer')
<x-modern.filter title="Filter Customers" icon="bx bx-search-alt" :resetUrl="route('customers.index')"
    :expanded="request()->anyFilled(['search'])">
    <div class="col-md-6">
        <x-modern.input label="Search Keyword" name="search" placeholder="Search by name, phone, or email..." :value="request('search')"
            icon="bx bx-search" />
    </div>
</x-modern.filter>

<x-modern.card title="Customers Management" icon="bx bx-smile">
    <x-slot name="actions">
        @can('create-customer')
        <x-modern.actions.button tag="a" href="{{ route('customers.create') }}" actionType="add" label="Add New" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Name', 'Phone', 'Email', 'Location', 'Joined', 'Actions']">
        @forelse($customers as $customer)
        <tr>
            <td class="align-middle">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
            <td class="align-middle fw-bold text-dark">{{ $customer->name }}</td>
            <td class="align-middle">{{ $customer->phone }}</td>
            <td class="align-middle">{{ $customer->email ?? '-' }}</td>
            <td class="align-middle">
                @if($customer->city || $customer->thana)
                    {{ $customer->thana ? $customer->thana . ', ' : '' }}{{ $customer->city }}
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td class="align-middle">{{ $customer->created_at->format('d M, Y') }}</td>
            <td class="align-middle">
                <div class="d-flex justify-content-center gap-2">
                    @can('edit-customer')
                    <x-modern.actions.button tag="a" href="{{ route('customers.edit', $customer->id) }}" actionType="edit" outline />
                    @endcan
                    
                    @can('delete-customer')
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button actionType="delete" type="submit" onclick="return confirm('Are you sure you want to delete this customer?');" outline />
                    </form>
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
                <h5 class="fw-bold">No Customers Found</h5>
                <p class="text-muted mb-0">Try adjusting your filters or create a new customer.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>
    
    <x-modern.pagination :collection="$customers" />
</x-modern.card>

@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to view the customer list.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection
