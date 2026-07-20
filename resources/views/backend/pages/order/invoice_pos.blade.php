@extends('backend.layouts.master')

@section('title', 'POS Receipt - ' . $order->order_number)

@section('content')
<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4" id="print-area">
                <style>
                    @media print {
                        @page {
                            margin: 0;
                            padding: 0;
                            size: 4in 6in;
                        }

                        /* Hide background elements */
                        header,
                        .vertical-menu,
                        .right-sidebar,
                        #preloader,
                        .print-hidden {
                            display: none !important;
                        }

                        body * {
                            visibility: hidden;
                        }

                        #print-area,
                        #print-area * {
                            visibility: visible;
                        }

                        /* Use FIXED positioning and VIEWPORT units to completely ignore background body width! */
                        #print-area {
                            position: fixed !important;
                            left: 0 !important;
                            top: 0 !important;
                            width: 100vw !important;
                            height: 100vh !important;
                            max-width: none !important;
                            max-height: none !important;
                            margin: 0 !important;
                            padding: 8mm !important;
                            box-sizing: border-box !important;
                            overflow: hidden !important;
                            background: white !important;
                            z-index: 999999 !important;
                        }
                    }

                    /* Thermal Printer Optimized Styles */
                    #print-area {
                        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                        font-size: 13px;
                        /* Slightly larger for 4x6 readability */
                        line-height: 1.4;
                        color: #000;
                        width: 100%;
                        max-width: 4in;
                        margin: 0 auto;
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

                    .gov-text {
                        font-size: 10px;
                        margin-bottom: 2px;
                    }

                    .company-name {
                        font-size: 13px;
                        font-weight: bold;
                        text-transform: uppercase;
                        margin: 4px 0;
                    }

                    .receipt-info {
                        font-size: 10px;
                    }

                    .two-col {
                        display: flex;
                        justify-content: space-between;
                        font-size: 10px;
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

                    .barcode-area {
                        text-align: center;
                        margin: 15px 0;
                    }

                    .receipt-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 2px;
                        font-size: 10px;
                    }

                    .receipt-table th {
                        text-align: left;
                        border-top: 1px solid #000;
                        border-bottom: 1px solid #000;
                        font-weight: bold;
                        padding: 2px 0;
                    }

                    .receipt-table td {
                        padding-top: 2px;
                        padding-bottom: 2px;
                        vertical-align: top;
                        line-height: 1.1;
                    }

                    .receipt-total {
                        border-top: 1px solid #000;
                        margin-top: 2px;
                        padding-top: 3px;
                        font-size: 10px;
                    }

                    .sub-row {
                        display: flex;
                        justify-content: flex-end;
                        margin-bottom: 2px;
                    }

                    .sub-row span:first-child {
                        width: 120px;
                        text-align: right;
                        padding-right: 10px;
                    }

                    .sub-row span:last-child {
                        width: 70px;
                        text-align: right;
                    }

                    .text-end {
                        text-align: right;
                    }

                    .text-center {
                        text-align: center;
                    }

                    .fw-bold {
                        font-weight: bold;
                    }

                    .receipt-footer {
                        margin-top: 10px;
                        font-size: 10px;
                        border-top: 1px dashed #000;
                        padding-top: 5px;
                    }
                </style>

                @php
                $shop_address = setting()->address ?? 'Dhaka, Bangladesh';
                $site_title = setting()->site_name ?? 'ULTIMATE ORGANIC LIFE LIMITED';
                $vat_no = setting()->vat_no ?? '005948789-0101';
                $currency = '৳';
                
                $isFirstOrder = $order->customer && \App\Models\Order::where('customer_id', $order->customer_id)->where('id', '<', $order->id)->count() === 0;
                @endphp

                <div class="mushak">Mushak: 6.3</div>
                <div class="receipt-header">
                    <div class="company-name">{{ $site_title }}</div>
                    <div class="receipt-info">{{ $shop_address }}</div>
                    <div class="receipt-info">VAT No: {{ $vat_no }}</div>
                </div>

                <div class="two-col">
                    <div class="col-left">
                        <div><b>Bill Date:</b> {{ $order->created_at->format('Y-m-d') }}</div>
                        <div><b>Name:</b> {{ $order->customer->name ?? 'Walk-in Customer' }}</div>
                        <div><b>Mobile:</b> {{ $order->customer->phone ?? '' }}</div>
                        <div><b>Address:</b> {{ $order->shipping_address ?? '' }}</div>
                    </div>
                    <div class="col-right">
                        <div><b>Order No:</b> {{ $order->order_number }}</div>
                    </div>
                </div>



                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">SL</th>
                            <th style="width: 40%;">Item</th>
                            <th style="width: 20%; text-align:right;">Price</th>
                            <th style="width: 10%; text-align:center;">Qty</th>
                            <th style="width: 20%; text-align:right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $item->variation->product->name ?? 'Unknown' }}
                                @if($item->variation->color || $item->variation->size)
                                    <br><span style="font-size: 9px; color: #555;">
                                        @php
                                            $attrStr = '';
                                            if($item->variation->color) $attrStr .= $item->variation->color->name;
                                            if($item->variation->size) {
                                                $attrStr .= $item->variation->color ? ' | ' . $item->variation->size->name : $item->variation->size->name;
                                            }
                                        @endphp
                                        {{ $attrStr }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                        @if($isFirstOrder)
                        <tr>
                            <td>{{ count($order->items) + 1 }}</td>
                            <td>Surprise Gift 🎁</td>
                            <td class="text-end">0.00</td>
                            <td class="text-center">1</td>
                            <td class="text-end">0.00</td>
                        </tr>
                        @endif
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

                    <div class="sub-row">
                        <span>Net Amount</span>
                        <span>{{ number_format($order->total_amount, 2) }}</span>
                    </div>

                    @php
                    $totalVat = $order->items->sum('sub_total') - ($order->items->sum('sub_total') / 1.05);
                    @endphp
                    @if ($totalVat > 0)
                    <div class="sub-row">
                        <span>VAT & SD (5%)</span>
                        <span>{{ number_format($totalVat, 2) }}</span>
                    </div>
                    @endif

                    @if ($order->due > 0)
                    <div class="sub-row">
                        <span>Due Amount</span>
                        <span>{{ number_format($order->due, 2) }}</span>
                    </div>
                    @endif
                </div>

                <div class="receipt-footer"
                    style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div style="flex: 1; padding-right: 15px;">
                        <div style="font-weight: bold; margin-bottom: 2px;">Note: All Product value is VAT Included.
                        </div>
                        <div style="margin-bottom: 5px; font-size: 10px; line-height: 1.2;">
                            <b>Amount in words:</b> <span style="text-transform: capitalize;">{{ class_exists('NumberFormatter') ? (new \NumberFormatter('en', \NumberFormatter::SPELLOUT))->format($order->total_amount) : '' }} Taka Only</span>
                        </div>
                        <div>
                            <div style="margin-bottom: 3px;"><b>Printed By:</b> {{ auth()->user()->name ?? 'System' }}
                            </div>
                            <div style="margin-bottom: 3px;"><b>Prepared By:</b> {{ $order->investigator->name ??
                                ($order->sales_man->name ?? 'System') }}</div>
                            <div><b>Printed Date:</b> {{ now()->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>
                    <div style="text-align: right; width: 100px; display: flex; justify-content: flex-end;">
                        {!! app(\Milon\Barcode\DNS2D::class)->getBarcodeSVG($order->order_number, 'QRCODE', 3, 3) !!}
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white border-0 p-3 pt-0 text-center print-hidden">
                <button class="btn btn-dark w-100 mb-2 py-2 fw-bold shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i> PRINT RECEIPT
                </button>
                <div class="row g-2">
                    @if(auth()->check() && auth()->user()->can('pack-order') && ($order->order_type ?? 'online') == 'online' && $order->order_status_id == 3)
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-success w-100 btn-sm fw-bold change-status-btn"
                            data-id="{{ $order->id }}" data-status="4">
                            <i class="bi bi-box-seam"></i> UPDATE AS PACKED
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if (request()->has('print'))
@push('scripts')
<script>
    window.onload = function() {
                window.print();
            };
</script>
@endpush
@endif

@push('scripts')
<script>
    $(document).ready(function() {
        $('.change-status-btn').on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const posId = btn.data('id');
            const statusId = btn.data('status');
            const originalHtml = btn.html();
            
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');

            $.ajax({
                url: "{{ route('orders.change-status', $order->id) }}",
                type: "POST",
                data: { order_status_id: statusId, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.status === 'error') {
                        alert(res.message);
                        btn.prop('disabled', false).html(originalHtml);
                        return;
                    }
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                        window.close();
                    } else {
                        window.location.href = "{{ route('orders.online') }}";
                    }
                },
                error: function() {
                    alert('Something went wrong while updating status');
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        // Auto-pack if scanned via QR Code
        @if (request()->query('action') == 'pack')
            setTimeout(function() {
                if ($('.change-status-btn').length > 0) {
                    $('.change-status-btn').trigger('click');
                } else {
                    // If button doesn't exist, it means order is already packed or they lack permission
                    alert('This order is already packed or cannot be updated.');
                    window.location.href = "{{ route('orders.online') }}";
                }
            }, 500);
        @endif
    });
</script>
@endpush