@extends('backend.layouts.master')

@section('title', 'Order Details #' . $order->order_number)

@section('content')

@can('view-order')
<div class="row">
    <div class="col-12">
        <x-modern.card title="Order Invoice #{{ $order->order_number }}" icon="bx bx-receipt">
            <x-slot name="actions">
                <x-modern.actions.button tag="a" href="javascript:history.back()" actionType="back" outline />
            </x-slot>

            <div class="row mb-4">
                <div class="col-sm-6">
                    <h6 class="mb-3 fw-bold text-uppercase text-muted">Customer Details:</h6>
                    <div>
                        <strong>{{ $order->customer->name ?? 'Unknown Customer' }}</strong>
                    </div>
                    <div>Email: {{ $order->customer->email ?? 'N/A' }}</div>
                    <div>Phone: {{ $order->customer->phone ?? 'N/A' }}</div>
                </div>

                <div class="col-sm-6">
                    <h6 class="mb-3 fw-bold text-uppercase text-muted">Shipping Address:</h6>
                    <div>
                        {{ $order->shipping_address ?? 'No address provided' }}
                    </div>
                    <div>{{ $order->city ?? '' }} {{ $order->thana ? ', ' . $order->thana : '' }}</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold text-uppercase text-muted">Current Status:</span>
                        <span class="badge" style="background-color: {{ $order->status->color_code }}20; color: {{ $order->status->color_code }}; border-radius: 8px; font-size: 14px;">
                            {{ $order->status->name }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <div class="fw-bold text-uppercase text-muted">Order Date:</div>
                    <div>{{ $order->created_at->format('d M, Y h:i A') }}</div>
                </div>
            </div>

            <div class="table-responsive-sm">
                <table class="table table-striped table-bordered align-middle text-center">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="text-start">Item</th>
                            <th>Unit Cost</th>
                            <th>Quantity</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="text-start fw-bold">
                                {{ $item->variation->product->name ?? 'Unknown Product' }}
                                <div class="text-muted small fw-normal">
                                    @php
                                        $attrStr = '';
                                        if($item->variation->color) $attrStr .= $item->variation->color->name;
                                        if($item->variation->size) {
                                            $attrStr .= $item->variation->color ? ' (' . $item->variation->size->name . ')' : $item->variation->size->name;
                                        }
                                    @endphp
                                    {{ $attrStr }} | SKU: {{ $item->variation->sku }}
                                </div>
                            </td>
                            <td>৳{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">৳{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-lg-4 col-sm-5 ms-auto">
                    <table class="table table-clear">
                        <tbody>
                            <tr>
                                <td class="text-start fw-bold text-muted">Subtotal</td>
                                <td class="text-end">৳{{ number_format($order->total_amount - $order->shipping_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold text-muted">Shipping Cost</td>
                                <td class="text-end">৳{{ number_format($order->shipping_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold text-dark fs-5"><strong>Total</strong></td>
                                <td class="text-end fw-bold text-primary fs-5"><strong>৳{{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-top">
                <p class="text-muted small text-center mb-0">Thank you for shopping with Buzz Bangladesh!</p>
            </div>
        </x-modern.card>
    </div>
</div>
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
