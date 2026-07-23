@extends('frontend.layouts.master')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">Order Confirmed</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('frontend.home') }}">Homepage</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">Order Confirmed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Order Success Content --}}
<div class="order-success-block md:py-20 py-10">
    <div class="container">
        <div class="max-w-2xl mx-auto">

            {{-- Success Icon + Heading --}}
            <div class="text-center mb-10">
                <div class="w-20 h-20 rounded-full bg-green flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="ph-bold ph-check text-4xl text-white"></i>
                </div>
                <div class="heading3 mb-2">Thank you, {{ $order->customer->name }}!</div>
                <p class="text-secondary">Your order has been placed successfully. We'll contact you shortly to confirm delivery.</p>
            </div>

            {{-- Order Info Card --}}
            <div class="bg-surface rounded-2xl p-6 mb-6">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <div class="caption1 text-secondary mb-1">Order Number</div>
                        <div class="text-title font-bold text-lg">{{ $order->order_number }}</div>
                    </div>
                    <div>
                        <div class="caption1 text-secondary mb-1">Status</div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white"
                                  style="background-color: {{ $order->status->color_code ?? '#FFC107' }}">
                                {{ $order->status->name ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="caption1 text-secondary mb-1">Payment Method</div>
                        <div class="text-title">Cash on Delivery</div>
                    </div>
                    <div>
                        <div class="caption1 text-secondary mb-1">Order Date</div>
                        <div class="text-title">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>
            </div>

            {{-- Delivery Info --}}
            <div class="border border-line rounded-2xl p-6 mb-6">
                <div class="heading6 mb-4 flex items-center gap-2">
                    <i class="ph ph-map-pin text-xl"></i>
                    Delivery Address
                </div>
                <div class="space-y-1 text-secondary">
                    <div><span class="text-black font-medium">{{ $order->customer->name }}</span></div>
                    <div>{{ $order->customer->phone }}</div>
                    @if($order->customer->email)
                    <div>{{ $order->customer->email }}</div>
                    @endif
                    <div>{{ $order->shipping_address }}</div>
                    <div>{{ $order->thana }}, {{ $order->city }}</div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="border border-line rounded-2xl p-6 mb-6">
                <div class="heading6 mb-4 flex items-center gap-2">
                    <i class="ph ph-shopping-bag text-xl"></i>
                    Items Ordered
                </div>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-3 pb-4 border-b border-line last:border-0 last:pb-0">
                        <div class="flex-grow">
                            <div class="text-title text-sm font-medium">{{ $item->product_name }}</div>
                            @if($item->color_name || $item->size_name)
                            <div class="caption1 text-secondary mt-0.5">
                                {{ collect([$item->color_name, $item->size_name])->filter()->implode(' / ') }}
                            </div>
                            @endif
                            <div class="caption1 text-secondary mt-0.5">Qty: {{ $item->quantity }} × ৳{{ number_format($item->unit_price, 2) }}</div>
                        </div>
                        <div class="text-title font-semibold flex-shrink-0">৳{{ number_format($item->total_price, 2) }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="mt-4 pt-4 border-t border-line space-y-2">
                    <div class="flex justify-between caption1 text-secondary">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between caption1 text-secondary">
                        <span>Shipping</span>
                        <span class="text-green font-medium">Free</span>
                    </div>
                    <div class="flex justify-between text-title font-bold text-base pt-2 border-t border-line">
                        <span>Total</span>
                        <span>৳{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($order->notes)
            <div class="border border-line rounded-2xl p-6 mb-6">
                <div class="heading6 mb-2 flex items-center gap-2">
                    <i class="ph ph-note-pencil text-xl"></i>
                    Order Notes
                </div>
                <p class="text-secondary caption1">{{ $order->notes }}</p>
            </div>
            @endif

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <a href="{{ route('frontend.shop') }}" class="button-main w-full text-center sm:basis-1/2">
                    Continue Shopping
                </a>
                <a href="{{ route('frontend.home') }}" class="button-main w-full text-center sm:basis-1/2 bg-white border border-black text-black hover:bg-black hover:text-white">
                    Back to Home
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
