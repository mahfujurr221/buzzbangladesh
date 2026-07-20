@extends('backend.layouts.master')

@section('title', 'Invoice #' . $order->order_number)

@section('content')
    <div class="row justify-content-center mt-3">
        <div class="col-lg-10">
            <!-- Toolbar -->
            <div class="d-print-none mb-3 d-flex justify-content-between align-items-center">
                <div>
                </div>
                <div class="d-flex gap-2">
                    @if(auth()->user()->can('pack-order') && ($order->order_type ?? 'online') == 'online' && $order->order_status_id == 3)
                        <button type="button" class="btn btn-success btn-sm change-status-btn" data-id="{{ $order->id }}" data-status="4"><i class="fa fa-box me-1"></i> Update As Packed</button>
                    @endif
                    
                    <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fa fa-print me-1"></i> Print</button>
                </div>
            </div>

            <!-- Invoice Sheet -->
            <div class="sheet shadow-sm" id="print-area">
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

                    #print-area {
                        font-family: 'Inter', sans-serif;
                        color: #333;
                        line-height: 1.4;
                        font-size: 13px;
                    }

                    .sheet {
                        background: #fff;
                        padding: 15mm;
                        position: relative;
                    }

                    }

                    @media print {
                        body * {
                            visibility: hidden;
                        }

                        #print-area,
                        #print-area * {
                            visibility: visible;
                        }

                        #print-area {
                            position: fixed;
                            left: 0;
                            top: 0;
                            width: 100%;
                            height: 100%;
                            background: #fff;
                            z-index: 99999;
                            padding: 10mm;
                            margin: 0;
                        }

                        .d-print-none {
                            display: none !important;
                        }
                    }

                    /* Header */
                    .header {
                        display: flex;
                        justify-content: space-between;
                        border-bottom: 2px solid #eee;
                        padding-bottom: 15px;
                        margin-bottom: 20px;
                    }

                    .company-info .logo {
                        max-height: 50px;
                        margin-bottom: 10px;
                    }

                    .company-name {
                        font-size: 18px;
                        font-weight: 700;
                        margin: 0;
                        color: #000;
                    }

                    .company-details {
                        color: #666;
                        font-size: 12px;
                    }

                    .invoice-info {
                        text-align: right;
                    }

                    .invoice-title {
                        font-size: 24px;
                        font-weight: 700;
                        color: #000;
                        text-transform: uppercase;
                        margin: 0;
                        line-height: 1;
                    }

                    .invoice-meta {
                        margin-top: 10px;
                        font-size: 13px;
                    }

                    .meta-item {
                        display: flex;
                        justify-content: flex-end;
                        margin-bottom: 2px;
                    }

                    .meta-label {
                        font-weight: 600;
                        margin-right: 10px;
                        color: #666;
                    }

                    /* Client Info */
                    .bill-to {
                        margin-bottom: 20px;
                        display: flex;
                    }

                    .bill-group {
                        width: 50%;
                    }

                    .group-label {
                        font-size: 11px;
                        text-transform: uppercase;
                        color: #888;
                        font-weight: 600;
                        margin-bottom: 5px;
                        letter-spacing: 0.5px;
                    }

                    .client-name {
                        font-weight: 700;
                        font-size: 14px;
                        margin: 0;
                    }

                    .client-details {
                        font-size: 12px;
                        color: #555;
                    }

                    /* Table */
                    .invoice-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }

                    .invoice-table th {
                        background: #f8f9fa;
                        padding: 8px 10px;
                        text-align: left;
                        font-weight: 600;
                        font-size: 12px;
                        text-transform: uppercase;
                        border-bottom: 1px solid #ddd;
                        color: #555;
                    }

                    .invoice-table td {
                        padding: 8px 10px;
                        border-bottom: 1px solid #eee;
                        vertical-align: top;
                    }

                    .text-end {
                        text-align: right !important;
                    }

                    .text-center {
                        text-align: center !important;
                    }

                    /* Summary */
                    .summary-section {
                        display: flex;
                        justify-content: flex-end;
                    }

                    .summary-table {
                        width: 300px;
                        border-collapse: collapse;
                    }

                    .summary-table td {
                        padding: 5px 0;
                        border-bottom: 1px dashed #eee;
                    }

                    .summary-label {
                        text-align: right;
                        padding-right: 15px;
                        color: #666;
                    }

                    .summary-value {
                        text-align: right;
                        font-weight: 600;
                    }

                    .grand-total td {
                        border-top: 2px solid #333;
                        border-bottom: none;
                        padding-top: 10px;
                        font-size: 15px;
                        font-weight: 700;
                        color: #000;
                    }

                    /* Footer */
                    .footer-notes {
                        margin-top: 30px;
                        padding-top: 15px;
                        border-top: 1px solid #eee;
                        font-size: 11px;
                        color: #777;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }

                    .signature-area {
                        margin-top: 40px;
                        display: flex;
                        justify-content: space-between;
                        padding: 0 20px;
                    }

                    .sign-box {
                        text-align: center;
                    }

                    .sign-line {
                        width: 150px;
                        border-bottom: 1px solid #ccc;
                        margin-bottom: 5px;
                    }

                    .sign-text {
                        font-size: 11px;
                        color: #888;
                    }
                </style>

                @php
                    $shop_logo = setting()->logo ? asset('backend/images/' . setting()->logo) : asset('backend/images/logo.png');

                    $shop_address = setting()->address ?? 'Dhaka, Bangladesh';
                    $shop_phone = setting()->phone ?? '';
                    $shop_email = setting()->email ?? '';
                    $currency = '৳';

                    // View Logic
                    $invoice_view = setting()->invoice_view_type ?? 'both';
                    $show_logo = $invoice_view == 'both' || $invoice_view == 'logo_only';
                    $show_text = $invoice_view == 'both' || $invoice_view == 'text_only';

                    $site_title = setting()->site_name ?? 'BuzzBangladesh';
                    $display_name = $site_title;
                    
                    $isFirstOrder = $order->customer && \App\Models\Order::where('customer_id', $order->customer_id)->where('id', '<', $order->id)->count() === 0;
                @endphp

                <!-- Header -->
                <div class="header">
                    <div class="company-info">
                        @if ($shop_logo && $show_logo)
                            <img src="{{ $shop_logo }}" alt="Logo" class="logo">
                        @endif

                        @if ($show_text)
                            <h2 class="company-name">{{ $display_name }}</h2>
                        @endif

                        <div class="company-details">
                            {{ $shop_address }}<br>
                            Phone: {{ $shop_phone }} | Email: {{ $shop_email }}
                        </div>
                    </div>
                    <div class="invoice-info">
                        <h1 class="invoice-title">INVOICE</h1>
                        <div class="invoice-meta">
                            <div class="meta-item">
                                <span class="meta-label">Invoice #:</span>
                                <span class="meta-value">{{ $order->invoice_no }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Order #:</span>
                                <span class="meta-value">{{ $order->order_number }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Date:</span>
                                <span>{{ $order->sale_date?->format('d M, Y') }} {{ $order->created_at?->timezone('Asia/Dhaka')->format('h:i A') }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Sold By:</span>
                                <span>{{ $order->sales_man->full_name ?? 'System' }} (ID: {{ $order->sale_by ?? 'N/A' }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bill To -->
                <div class="bill-to">
                    <div class="bill-group">
                        <div class="group-label">Bill To</div>
                        <h4 class="client-name">{{ $order->customer->name ?? 'Walk-in Customer' ?? 'Walk-in Customer' }}</h4>
                        @if ($order->customer)
                            <div class="client-details">
                                Phone: {{ $order->customer->phone ?? '' }}<br>
                                {{ $order->shipping_address ?? '' }}
                            </div>
                        @else
                            <div class="client-details">Cash Sales</div>
                        @endif
                    </div>
                </div>

                <!-- Items -->
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 45%;">Item Description</th>
                            <th class="text-center" style="width: 15%;">Qty</th>
                            <th class="text-end" style="width: 15%;">Price</th>
                            <th class="text-end" style="width: 20%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <b>{{ $item->variation->product->name ?? 'Unknown' }}</b>
                                    @if($item->variation->color || $item->variation->size)
                                        <br><span style="font-size: 11px; color: #777;">
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
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 0) }} ৳</td>
                                <td class="text-end">{{ number_format($item->total_price, 0) }} ৳</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Summary -->
                <div class="summary-section">
                    <table class="summary-table">
                        <tr>
                            <td class="summary-label">Subtotal</td>
                            <td class="summary-value">{{ number_format($order->total_amount - $order->shipping_cost, 0) }} ৳</td>
                        </tr>
                        @if ($order->shipping_cost > 0)
                            <tr>
                                <td class="summary-label">Shipping</td>
                                <td class="summary-value">+{{ number_format($order->shipping_cost, 0) }} ৳</td>
                            </tr>
                        @endif
                        <tr class="grand-total">
                            <td class="summary-label">Net Payable</td>
                            <td class="summary-value">{{ number_format($order->total_amount, 0) }} ৳</td>
                        </tr>
                    </table>
                </div>

                @php
                    $digit = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                    $amountInWords = ucwords($digit->format($order->total_amount));
                @endphp
                <div style="margin-top: 5px; font-size: 12px; font-weight: 500; font-style: italic;">
                    In Words: {{ $amountInWords }} Taka Only.
                </div>

                <!-- Signatures -->
                <div class="signature-area">
                    <div class="sign-box">
                        <div class="sign-line"></div>
                        <div class="sign-text">Customer Signature</div>
                    </div>
                    <div class="sign-box">
                        <div class="sign-line"></div>
                        <div class="sign-text">Authorized Signature</div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="footer-notes">
                    <div>
                        Thank you for your business!
                    </div>
                    <div>
                        Generated by {{ config('app.name') }}
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
                url: "{{ route('change.order_status') }}",
                type: "GET",
                data: { pos_id: posId, status: statusId },
                success: function(res) {
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                    window.location.reload();
                },
                error: function() {
                    alert('Something went wrong while updating status');
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    });
</script>
@endpush
