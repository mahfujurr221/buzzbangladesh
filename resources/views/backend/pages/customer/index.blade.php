@extends('backend.layouts.master')

@section('title', 'Customers Management')

@section('content')
<div class="row">
    <div class="col-12">
        <x-modern.card title="All Customers">
            <div class="d-flex justify-content-end mb-3">
                @can('create-customer')
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Add New Customer
                </a>
                @endcan
            </div>
            
            <x-modern.table :headers="['Name', 'Phone', 'Email', 'Location', 'Joined', 'Actions']">
                @forelse($customers as $customer)
                    <tr>
                        <td class="fw-bold">{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>
                            @if($customer->city || $customer->thana)
                                {{ $customer->thana ? $customer->thana . ', ' : '' }}{{ $customer->city }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $customer->created_at->format('d M, Y') }}</td>
                        <td>
                            @can('edit-customer')
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            @endcan
                            
                            @can('delete-customer')
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No customers found.</td>
                    </tr>
                @endforelse
            </x-modern.table>
            
            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </x-modern.card>
    </div>
</div>
@endsection
