@extends('backend.layouts.master')

@section('title', 'Print Barcode')

@push('styles')
    <style>
    @media print {
        .no-print,
        .d-print-none,
        .card,
        .row.no-print {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
        }

        body {
            visibility: visible !important;
        }

        #printable_area {
            display: block !important;
            position: static !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .barcode-label {
            border: 1px dashed #000 !important;
        }
    }

    .barcode-label {
        border: 1px dashed #ccc;
        padding: 10px;
        margin: 5px;
        text-align: center;
        width: 200px;
        display: inline-block;
        page-break-inside: avoid;
        vertical-align: top;
    }

    .barcode-svg svg {
        width: 100%;
        height: 50px;
    }
    </style>
@endpush

@section('content')
<div class="row no-print d-print-none mb-2">
    <div class="col-12 d-print-none">
        <x-modern.card title="Print Barcode for: {{ $variation->product->name }}" icon="bx bx-barcode" class="mb-2">
            
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm" style="max-width: 200px;">
                    <span class="input-group-text bg-white text-muted">Qty</span>
                    <input type="number" id="print_qty" class="form-control text-center fw-bold" value="1" min="1" max="500">
                </div>
                
                <x-modern.actions.button label="Update" icon="bx bx-refresh" variant="primary" onclick="generateLabels()" class="shadow-sm" />
                <x-modern.actions.button label="Print" icon="bx bx-printer" variant="dark" onclick="window.print()" class="shadow-sm" />
                <x-modern.actions.button tag="a" href="{{ route('products.barcodes', $variation->product_id) }}" label="Back" variant="secondary" outline class="shadow-sm" />
            </div>
            
        </x-modern.card>
    </div>
</div>

<div class="container-fluid bg-white p-4" id="printable_area">
    <!-- Labels will be injected here -->
</div>

<template id="label_template">
    <div class="barcode-label d-flex flex-column align-items-center justify-content-center">
        <div class="fw-bold text-truncate" style="font-size: 14px;">{{ $variation->product->name }}</div>
        <div class="small">
            @if($variation->size) {{ $variation->size->name }} @endif
            @if($variation->size && $variation->color) / @endif
            @if($variation->color) {{ $variation->color->name }} @endif
        </div>
        <div class="fw-bold py-1">৳{{ number_format($variation->sale_price ?? $variation->regular_price, 2) }}</div>
        <div class="barcode-svg">{!! $barcode !!}</div>
        <div class="small text-muted">{{ $variation->sku }}</div>
    </div>
</template>

@endsection

@push('scripts')
<script>
    function generateLabels() {
        let qty = $('#print_qty').val();
        let container = $('#printable_area');
        let template = document.getElementById('label_template').innerHTML;
        
        container.empty();
        
        for (let i = 0; i < qty; i++) {
            container.append(template);
        }
    }

    // Init
    $(document).ready(function() {
        generateLabels();
    });
</script>
@endpush
