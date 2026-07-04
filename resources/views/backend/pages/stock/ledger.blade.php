@extends('backend.layouts.master')

@section('title', 'Stock Ledger History')

@section('content')
<div class="row">
    <div class="col-12">
        <x-modern.card title="Stock Movement History">
            <div class="d-flex justify-content-between mb-3">
                <p class="text-muted mb-0">This ledger records every time stock is added to a product variation.</p>
                <a href="{{ route('stocks.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-store-alt me-1"></i> Stock Overview
                </a>
            </div>
            
            <x-modern.table :headers="['Date & Time', 'Product', 'Variation (SKU)', 'Added Qty', 'Cost Price', 'Entered By', 'Note / Ref']">
                @forelse($ledgers as $ledger)
                    @php
                        $var = $ledger->variation;
                        $variantName = '';
                        if($var && $var->color) $variantName .= $var->color->name;
                        if($var && $var->color && $var->size) $variantName .= ' - ';
                        if($var && $var->size) $variantName .= $var->size->name;
                        if($variantName == '') $variantName = 'Default';
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $ledger->created_at->format('d M, Y') }}</div>
                            <div class="small text-muted">{{ $ledger->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            <a href="{{ route('stocks.manage', $ledger->product_id) }}" class="fw-bold text-primary">
                                {{ $ledger->product->name ?? 'Deleted Product' }}
                            </a>
                        </td>
                        <td>
                            {{ $variantName }}
                            <div class="text-muted small">{{ $var->sku ?? 'N/A' }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success fs-6">+{{ $ledger->quantity_added }}</span>
                        </td>
                        <td class="text-end">
                            ৳{{ number_format($ledger->purchase_price, 2) }}
                        </td>
                        <td>
                            <span class="badge bg-label-secondary">
                                <i class="bx bx-user me-1"></i> {{ $ledger->creator->name ?? 'System' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $ledger->note }}">
                                {{ $ledger->note ?? '-' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No stock ledger records found.</td>
                    </tr>
                @endforelse
            </x-modern.table>
            
            <div class="mt-3">
                {{ $ledgers->links() }}
            </div>
        </x-modern.card>
    </div>
</div>
@endsection
