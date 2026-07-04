@extends('backend.layouts.master')

@section('title', 'Stock Management')

@section('content')
<div class="row">
    <div class="col-12">
        <x-modern.card title="Product Stock Overview">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('stocks.ledger') }}" class="btn btn-outline-primary">
                    <i class="bx bx-history me-1"></i> View Stock Ledger
                </a>
            </div>
            <x-modern.table :headers="['Product Name', 'Category', 'Total Variations', 'Total Stock', 'Actions']">
                @forelse($products as $product)
                    @php
                        $totalStock = $product->variations->sum('stock_quantity');
                    @endphp
                    <tr>
                        <td class="fw-bold">{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ $product->variations->count() }}</td>
                        <td>
                            <span class="badge {{ $totalStock > 10 ? 'bg-success' : ($totalStock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $totalStock }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('stocks.manage', $product->id) }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-store-alt me-1"></i> Manage Stock
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No products found.</td>
                    </tr>
                @endforelse
            </x-modern.table>
            
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </x-modern.card>
    </div>
</div>
@endsection
