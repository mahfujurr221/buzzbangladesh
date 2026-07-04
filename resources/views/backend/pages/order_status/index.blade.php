@extends('backend.layouts.master')

@section('title', 'Order Statuses')

@section('content')

@can('list-order-status')
<x-modern.card title="Order Statuses Overview" icon="bx bx-loader">
    <x-slot name="actions">
        @can('create-order-status')
        <x-modern.actions.button tag="a" href="{{ route('order-statuses.create') }}" actionType="add" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Name', 'Color', 'Default', 'Actions']" tableClass="text-center">
        @forelse($statuses as $status)
            <tr>
                <td class="align-middle">{{ $loop->iteration }}</td>
                <td class="align-middle fw-bold text-dark">{{ $status->name }}</td>
                <td class="align-middle">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <span class="rounded-circle" style="width: 15px; height: 15px; background-color: {{ $status->color_code }}; display: inline-block;"></span>
                        <span class="badge bg-light text-dark border">{{ $status->color_code }}</span>
                    </div>
                </td>
                <td class="align-middle">
                    @if($status->is_default)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1" style="border-radius: 20px;">
                            <i class="bx bx-check-circle me-1"></i> Default
                        </span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="align-middle">
                    <div class="d-flex justify-content-center gap-2">
                        @can('edit-order-status')
                        <x-modern.actions.button tag="a" href="{{ route('order-statuses.edit', $status->id) }}" actionType="edit" outline />
                        @endcan
                        
                        @can('delete-order-status')
                            @if(!$status->is_default)
                            <form action="{{ route('order-statuses.destroy', $status->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this status?');">
                                @csrf
                                @method('DELETE')
                                <x-modern.actions.button type="submit" actionType="delete" outline />
                            </form>
                            @else
                                <x-modern.actions.button type="button" actionType="delete" outline disabled title="Cannot delete the default status" />
                            @endif
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center p-5 text-muted">
                    <div class="mb-3">
                        <i class="bx bx-loader text-light" style="font-size: 80px;"></i>
                    </div>
                    <h5 class="fw-bold">No Order Statuses Found</h5>
                    <p class="text-muted mb-4">Get started by creating your first order status.</p>
                    @can('create-order-status')
                        <x-modern.actions.button tag="a" href="{{ route('order-statuses.create') }}" actionType="add" />
                    @endcan
                </td>
            </tr>
        @endforelse
    </x-modern.table>
</x-modern.card>
@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to view this module.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection
