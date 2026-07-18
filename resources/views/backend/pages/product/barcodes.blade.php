@extends('backend.layouts.master')
@section('title', 'Barcodes - ' . $product->name)

@section('content')
<div class="row">
    <div class="col-12">
        <x-modern.card title="Barcodes for {{ $product->name }}" class="mb-4" icon="bx bx-barcode">
            
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>SKU / Code</th>
                            <th>Details (Size / Color)</th>
                            <th>Price</th>
                            <th class="text-center">Barcode Preview</th>
                            <th style="width: 120px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($variations as $variation)
                        <tr>
                            <td class="fw-bold">{{ $variation->sku }}</td>
                            <td>
                                {!! $variation->size ? $variation->size->name : '<span class="text-muted">—</span>' !!}
                                / 
                                {!! $variation->color ? $variation->color->name : '<span class="text-muted">—</span>' !!}
                            </td>
                            <td class="fw-bold text-primary">৳{{ number_format($variation->sale_price ?? $variation->regular_price, 2) }}</td>
                            <td class="text-center bg-light">
                                <svg class="barcode-svg" data-value="{{ $variation->sku }}"></svg>
                            </td>
                            <td class="text-center">
                                <x-modern.actions.button tag="a" href="{{ route('variations.barcode', $variation->id) }}" label="Print" icon="bx bx-printer" variant="primary" size="sm" />
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No variations found for this product.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <x-modern.actions.button tag="a" href="{{ route('products.index') }}" label="Back to Products" icon="bx bx-arrow-back" variant="secondary" outline />
            </div>
            
        </x-modern.card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Generate barcodes for screen preview
        const barcodeElements = document.querySelectorAll('.barcode-svg');
        
        barcodeElements.forEach(function(svg) {
            const value = svg.getAttribute('data-value');
            if (value) {
                // Generate SVG for the screen
                JsBarcode(svg, value, {
                    format: "CODE128",
                    displayValue: true,
                    fontSize: 14,
                    margin: 5,
                    width: 1.5,
                    height: 40
                });
            }
        });
    });
</script>
@endpush
