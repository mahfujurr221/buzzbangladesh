@extends('backend.layouts.master')

@section('title', 'Manage Stock: ' . $product->name)

@section('content')
<div class="row">
    <div class="col-12">
        <x-modern.card title="Stock Entry Matrix: {{ $product->name }}">
            
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bx bx-info-circle fs-4 me-2"></i>
                <div>
                    <strong>How to use:</strong> Enter the quantity of new stock you have received in the "Add Stock" column. This amount will be added to the Current Stock. You can also optionally update the purchase or sale price for the new batch.
                </div>
            </div>

            <form action="{{ route('stocks.store', $product->id) }}" method="POST">
                @csrf
                
                <div class="mt-4">
                    <x-modern.table :headers="['Variation (SKU)', 'Current Stock', 'Add Stock (+)', 'New Purchase Price (৳)', 'New Sale Price (৳)']" tableClass="text-center">
                        @foreach($product->variations as $index => $var)
                                @php
                                    $variantName = '';
                                    if($var->color) $variantName .= $var->color->name;
                                    if($var->color && $var->size) $variantName .= ' - ';
                                    if($var->size) $variantName .= $var->size->name;
                                    if($variantName == '') $variantName = 'Default';
                                @endphp
                                <tr>
                                    <td class="text-start fw-bold">
                                        {{ $variantName }}
                                        <div class="text-muted small fw-normal">{{ $var->sku }}</div>
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
                    <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bx bx-save me-1"></i> Save Stock Entries
                    </button>
                </div>
            </form>
        </x-modern.card>
    </div>
</div>
@endsection
