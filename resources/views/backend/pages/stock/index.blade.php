@extends('backend.layouts.master')

@section('title', 'Stock Management')

@section('content')

@can('list-stock')
<x-modern.filter action="{{ route('stocks.index') }}" resetUrl="{{ route('stocks.index') }}" title="Filter Stocks" icon="bx bx-search-alt" :expanded="request()->anyFilled(['search'])">
    <div class="col-md-6">
        <x-modern.input label="Search Keyword" name="search" value="{{ request('search') }}" placeholder="Search product by name..." icon="bx bx-search" />
    </div>
</x-modern.filter>

<x-modern.card title="Product Stock Overview" icon="bx bx-data">
    <x-slot name="actions">
        <x-modern.actions.button tag="a" href="{{ route('stocks.ledger') }}" label="View Ledger" icon="bx bx-history" variant="secondary" size="sm" />
    </x-slot>

    <x-modern.table :headers="['#', 'Product Name', 'Category', 'Variations', 'Total Stock', 'Actions']">
        @forelse($products as $product)
            @php
                $totalStock = $product->variations->sum('stock_quantity');
            @endphp
            <tr>
                <td class="align-middle text-center">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                <td class="align-middle fw-bold text-dark">{{ $product->name }}</td>
                <td class="align-middle">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="border-radius: 6px;">{{ $product->category->name ?? 'N/A' }}</span>
                </td>
                <td class="align-middle text-center">
                    <span class="badge bg-light text-dark border">{{ $product->variations->count() }} Variants</span>
                </td>
                <td class="align-middle text-center">
                    @if($totalStock > 10)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1" style="border-radius: 20px;">{{ $totalStock }}</span>
                    @elseif($totalStock > 0)
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1" style="border-radius: 20px;">{{ $totalStock }}</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1" style="border-radius: 20px;">Out of Stock</span>
                    @endif
                </td>
                <td class="align-middle text-center">
                    @can('manage-stock')
                    <x-modern.actions.button tag="a" href="{{ route('stocks.manage', $product->id) }}" label="Manage" actionType="edit" outline />
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center p-5 text-muted">
                    <div class="mb-3">
                        <i class="bx bx-data text-light" style="font-size: 80px;"></i>
                    </div>
                    <h5 class="fw-bold">No Products Found</h5>
                    <p class="text-muted mb-0">Try adjusting your filters.</p>
                </td>
            </tr>
        @endforelse
    </x-modern.table>
    
    <x-modern.pagination :collection="$products" />
</x-modern.card>

@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to view the stock list.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection
