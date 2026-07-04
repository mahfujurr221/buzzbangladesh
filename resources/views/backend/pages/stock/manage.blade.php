@extends('backend.layouts.master')

@section('title', 'Manage Stock: ' . $product->name)

@section('content')
<div class="row">
    <div class="col-12">
        <x-modern.card title="Stock Entry Matrix: {{ $product->name }}">
            
            <div class="mb-4">
                <x-modern.input id="variantSearchInput" placeholder="Search variants by name, color, or size..." icon="bx bx-search" />
            </div>

            <form action="{{ route('stocks.store', $product->id) }}" method="POST">
                @csrf
                
                <div class="mt-4">
                    <x-modern.table :headers="['Product Variation', 'Current Stock', 'Add Stock (+)', 'New Purchase Price (৳)', 'New Sale Price (৳)']" tableClass="text-center">
                        @foreach($product->variations as $index => $var)
                                @php
                                    $attrStr = '';
                                    if($var->color) $attrStr .= $var->color->name;
                                    if($var->size) {
                                        $attrStr .= $var->color ? '(' . $var->size->name . ')' : $var->size->name;
                                    }
                                @endphp
                                <tr class="variant-row">
                                    <td class="text-start fw-bold">
                                        {{ $product->name }}
                                        @if($attrStr)
                                            <div class="text-muted small fw-normal">{{ $attrStr }}</div>
                                        @endif
                                        <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $var->id }}">
                                    </td>
                                    <td>
                                        <span class="badge {{ $var->stock_quantity > 10 ? 'bg-success' : ($var->stock_quantity > 0 ? 'bg-warning' : 'bg-danger') }} fs-6">
                                            {{ $var->stock_quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <x-modern.input type="number" name="variations[{{ $index }}][add_quantity]" containerClass="mb-0" placeholder="e.g. 50" class="text-center fw-bold text-primary" />
                                    </td>
                                    <td>
                                        <x-modern.input type="number" step="0.01" name="variations[{{ $index }}][purchase_price]" containerClass="mb-0" value="{{ $var->purchase_price }}" placeholder="Cost Price" class="text-center" />
                                    </td>
                                    <td>
                                        <x-modern.input type="number" step="0.01" name="variations[{{ $index }}][sale_price]" containerClass="mb-0" value="{{ $var->sale_price }}" placeholder="Selling Price" class="text-center" />
                                    </td>
                                </tr>
                            @endforeach
                    </x-modern.table>
                </div>

                <div class="mt-4">
                    <x-modern.input label="Ledger Note / Reference (Optional)" name="note" placeholder="e.g. Restock from Supplier XYZ, Invoice #12345" />
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <x-modern.actions.button tag="a" href="{{ route('stocks.index') }}" actionType="back" label="Back to List" outline />
                    <x-modern.actions.button type="submit" actionType="save" label="Save Stock Entries" size="lg" />
                </div>
            </form>
        </x-modern.card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('variantSearchInput');
        const rows = document.querySelectorAll('.variant-row');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.querySelector('td:first-child').textContent.toLowerCase();
                    if (text.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
