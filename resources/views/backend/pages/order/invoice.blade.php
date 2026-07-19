@extends('backend.layouts.master')

@section('title', 'POS Receipt #' . $order->order_number)

@push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print-hidden {
                display: none !important;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
            #print-area {
                width: 80mm !important;
                margin: 0 !important;
                padding: 10px !important;
                background: white !important;
                z-index: 999999 !important;
            }
        }

        /* Thermal Printer Optimized Styles */
        #print-area {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            line-height: 1.4;
            color: #000;
            width: 100%;
            max-width: 80mm;
            margin: 0 auto;
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .mushak {
            text-align: right;
            font-size: 10px;
            font-weight: bold;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 4px 0;
        }

        .receipt-info {
            font-size: 11px;
        }

        .two-col {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-top: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .col-left {
            width: 55%;
        }

        .col-right {
            width: 45%;
            text-align: right;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 11px;
            margin-top: 5px;
        }

        .receipt-table th {
            text-align: left;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            font-weight: bold;
            padding: 4px 0;
        }

        .receipt-table td {
            padding-top: 4px;
            padding-bottom: 4px;
            vertical-align: top;
            line-height: 1.2;
        }

        .receipt-total {
            border-top: 1px dashed #000;
            margin-top: 2px;
            padding-top: 5px;
            font-size: 11px;
        }

        .sub-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .sub-row span:first-child {
            text-align: right;
            flex-grow: 1;
            padding-right: 15px;
        }

        .sub-row span:last-child {
            width: 70px;
            text-align: right;
            font-weight: bold;
        }

        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }

        .receipt-footer {
            margin-top: 15px;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 10px;
            text-align: left;
        }
    </style>
@endpush

@section('content')

@can('view-order')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center print-hidden">
                <a href="{{ route('orders.online') }}" class="btn btn-sm btn-light border"><i class="bx bx-arrow-back"></i> Back to Orders</a>
                <span class="badge" style="background-color: {{ $order->status->color_code }}20; color: {{ $order->status->color_code }}; border-radius: 6px;">{{ $order->status->name }}</span>
            </div>
            
            <div class="card-body d-flex justify-content-center pt-2">
                <!-- Receipt Print Area -->
                <div id="print-area">
                    @php
                        $site_title = 'Buzz Bangladesh';
                        $shop_address = 'Dhaka, Bangladesh';
                        $vat_no = 'N/A';
                    @endphp

                    <div class="mushak">Mushak: 6.3</div>
                    <div class="receipt-header">
                        <div class="company-name">{{ $site_title }}</div>
                        <div class="receipt-info">{!! nl2br(e($shop_address)) !!}</div>
                        <div class="receipt-info">VAT No: {{ $vat_no }}</div>
                    </div>

                    <div class="two-col">
                        <div class="col-left">
                            <div><b>Bill Date:</b> {{ $order->created_at->format('Y-m-d') }}</div>
                            <div><b>Name:</b> {{ $order->customer->name ?? 'Walk-in Customer' }}</div>
                            <div><b>Mobile:</b> {{ $order->customer->phone ?? '' }}</div>
                            <div><b>Address:</b> {{ $order->shipping_address ?? '' }} {{ $order->city }} {{ $order->thana }}</div>
                        </div>
                        <div class="col-right">
                            <div><b>Order No:</b> {{ $order->order_number }}</div>
                            <div><b>Time:</b> {{ $order->created_at->format('h:i A') }}</div>
                        </div>
                    </div>

                    <table class="receipt-table">
                        <thead>
                            <tr>
                                <th style="width: 10%;">SL</th>
                                <th style="width: 45%;">Item</th>
                                <th style="width: 15%; text-align:center;">Qty</th>
                                <th style="width: 30%; text-align:right;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $item->variation->product->name ?? 'Unknown' }}
                                        @if($item->variation->color || $item->variation->size)
                                        <br>
                                        <span style="font-size: 9px; color: #555;">
                                            @php
                                                $attrStr = '';
                                                if($item->variation->color) $attrStr .= 'C: ' . $item->variation->color->name;
                                                if($item->variation->size) {
                                                    $attrStr .= $item->variation->color ? ' | S: ' . $item->variation->size->name : 'S: ' . $item->variation->size->name;
                                                }
                                            @endphp
                                            {{ $attrStr }}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="receipt-total">
                        <div class="sub-row">
                            <span>Gross</span>
                            <span>{{ number_format($order->total_amount - $order->shipping_cost, 2) }}</span>
                        </div>

                        @if ($order->shipping_cost > 0)
                        <div class="sub-row">
                            <span>Others Cost(Shipping)</span>
                            <span>{{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        @endif

                        <div class="sub-row" style="font-size: 13px; margin-top: 5px; padding-top: 5px; border-top: 1px dashed #000;">
                            <span><b>Net Amount</b></span>
                            <span><b>{{ number_format($order->total_amount, 2) }}</b></span>
                        </div>
                    </div>

                    <div class="receipt-footer">
                        <div style="font-weight: bold; margin-bottom: 2px;">Note: All Product value is VAT Included.</div>
                        <div style="margin-bottom: 5px; font-size: 9px; line-height: 1.2;">
                            পণ্য গ্রহণের সময় চেক করে নিন। ডেলিভারি ম্যান চলে যাওয়ার পর কোন অভিযোগ গ্রহণযোগ্য নয়।
                        </div>
                        <div style="margin-top: 8px;">
                            <div style="margin-bottom: 3px;"><b>Printed By:</b> {{ auth()->user()->name ?? 'System' }}</div>
                            <div><b>Printed Date:</b> {{ now()->format('Y-m-d h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white border-0 p-3 pt-0 text-center print-hidden">
                <button class="btn btn-dark w-100 py-3 fw-bold shadow-sm" onclick="window.print()">
                    <i class="bx bx-printer me-2"></i> PRINT RECEIPT
                </button>
            </div>
        </div>
    </div>
</div>
@else
<div class="card mt-3">
    <div class="card-body text-center py-5">
        <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        <h4 class="fw-bold mt-3">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to view this module.</p>
    </div>
</div>
@endcan

@endsection

@if (request()->has('print'))
@push('scripts')
<script>
    window.onload = function() {
        setTimeout(() => {
            window.print();
        }, 500);
    };
</script>
@endpush
@endif
