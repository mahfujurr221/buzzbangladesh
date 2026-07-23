@extends('frontend.layouts.master')
@section('title', 'Track Order - ' . ($setting->site_name ?? 'Buzz Bangladesh'))

@push('styles')
<style>
    :root { --brand: #9A0002; --brand-dark: #6b0001; }
    
    .track-order-section {
        padding: 80px 0;
        background: #f8f8f8;
    }

    .track-box {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        padding: 40px;
        max-width: 600px;
        margin: 0 auto;
        border: 1px solid rgba(154,0,2,0.1);
    }

    .track-heading {
        font-size: 24px;
        font-weight: 700;
        color: #111;
        margin-bottom: 10px;
        text-align: center;
    }

    .track-desc {
        color: #666;
        text-align: center;
        margin-bottom: 30px;
        font-size: 15px;
    }

    .track-form-group {
        margin-bottom: 20px;
    }

    .track-input {
        width: 100%;
        padding: 14px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 15px;
        outline: none;
        transition: all 0.3s;
    }

    .track-input:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 4px rgba(154,0,2,0.1);
    }

    .track-btn {
        width: 100%;
        padding: 14px;
        background: var(--brand);
        color: white;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .track-btn:hover {
        background: var(--brand-dark);
        transform: translateY(-2px);
    }

    .order-details-box {
        margin-top: 40px;
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }

    .order-status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .status-pending { background: #fef08a; color: #854d0e; }
    .status-processing { background: #bfdbfe; color: #1e40af; }
    .status-shipped { background: #fbcfe8; color: #be185d; }
    .status-delivered { background: #bbf7d0; color: #166534; }
    .status-canceled { background: #fecaca; color: #991b1b; }
    
    .table-details { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .table-details th, .table-details td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
    .table-details th { font-weight: 600; color: #444; background: #f9fafb; }
</style>
@endpush

@section('content')

{{-- ===== BANNER ===== --}}
<div class="breadcrumb-block style-img">
    <div class="breadcrumb-main bg-[#FDF8EE] overflow-hidden relative">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">Track Order</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('frontend.home') }}">Homepage</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">Track Order</div>
                    </div>
                </div>
            </div>
            <!-- Overlay removed -->
        </div>
    </div>
</div>

<section class="track-order-section">
    <div class="container">
        <div class="track-box">
            <h2 class="track-heading">Track Your Order</h2>
            <p class="track-desc">Enter your order number below to check the current status of your shipment.</p>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('frontend.track.order') }}" method="GET">
                <div class="track-form-group">
                    <label for="order_number" class="block text-sm font-medium text-gray-700 mb-1">Order Number</label>
                    <input type="text" id="order_number" name="order_number" class="track-input" placeholder="e.g. BZ-20231025-ABCDE" value="{{ request('order_number') }}" required>
                </div>
                <button type="submit" class="track-btn">Track Order</button>
            </form>
        </div>

        @if(isset($order))
        <div class="order-details-box max-w-[800px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-4 mb-4 gap-4">
                <div>
                    <h3 class="text-xl font-bold">Order #{{ $order->order_number }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <span class="order-status-badge" style="background-color: {{ $order->status->color_code ?? '#FFC107' }}; color: #fff;">
                        {{ $order->status->name ?? 'Pending' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="font-bold text-gray-800 mb-2">Shipping Information</h4>
                    <p class="text-gray-600 text-sm">
                        {{ $order->customer->name ?? 'N/A' }}<br>
                        {{ $order->customer->phone ?? 'N/A' }}<br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->thana }}, {{ $order->city }}
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-2">Order Summary</h4>
                    <p class="text-gray-600 text-sm flex justify-between">
                        <span>Payment Method:</span>
                        <span class="font-medium uppercase">{{ $order->payment_method }}</span>
                    </p>
                    <p class="text-gray-600 text-sm flex justify-between mt-1">
                        <span>Total Amount:</span>
                        <span class="font-medium font-bold">৳{{ number_format($order->total_amount, 2) }}</span>
                    </p>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-2">Items</h4>
                <div class="overflow-x-auto">
                    <table class="table-details">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th class="text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $item->product_name }}</div>
                                    @if($item->color_name || $item->size_name)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($item->color_name) Color: {{ $item->color_name }} @endif
                                        @if($item->color_name && $item->size_name) | @endif
                                        @if($item->size_name) Size: {{ $item->size_name }} @endif
                                    </div>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-right font-medium">৳{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
