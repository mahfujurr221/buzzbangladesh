@extends('frontend.layouts.master')

@section('content')

{{-- Order Success Content (with top padding to account for fixed header since breadcrumb is removed) --}}
<div class="order-success-block md:py-20 py-10 pt-[134px] bg-gray-50">
    <div class="container">
        <div class="max-w-2xl mx-auto">
            
            <div class="bg-white shadow-2xl rounded-3xl p-6 md:p-8 border-t-4" style="border-top-color: #9A0002;">
                {{-- Success Heading --}}
                <div class="text-center mb-6">
                    <div class="text-3xl font-bold mb-2" style="color: #9A0002;">Thank you, {{ $order->customer->name }}!</div>
                    <p class="text-secondary text-base">Your order has been placed successfully. We'll contact you shortly to confirm delivery.</p>
                </div>

                {{-- Order Progress Bar --}}
                @include('frontend.components.order-progress', ['order' => $order])

                {{-- Order Info Card --}}
                <div class="bg-gray-50 rounded-2xl p-5 mb-5 hover:shadow-md transition-shadow duration-300 border border-gray-100">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <div class="caption1 text-secondary uppercase tracking-wider font-semibold mb-1">Order Number</div>
                            <div class="text-title font-bold text-xl">{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div class="caption1 text-secondary uppercase tracking-wider font-semibold mb-1">Status</div>
                            <div>
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold text-white shadow-sm"
                                      style="background-color: {{ $order->status->color_code ?? '#FFC107' }}">
                                    {{ $order->status->name ?? 'Pending' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="caption1 text-secondary uppercase tracking-wider font-semibold mb-1">Payment Method</div>
                            <div class="text-title font-medium">Cash on Delivery</div>
                        </div>
                        <div>
                            <div class="caption1 text-secondary uppercase tracking-wider font-semibold mb-1">Order Date</div>
                            <div class="text-title font-medium">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5 mb-6">
                    {{-- Delivery Info --}}
                    <div class="border border-line rounded-2xl p-5 hover:shadow-md transition-shadow duration-300">
                        <div class="heading6 mb-4 flex items-center gap-2" style="color: #9A0002;">
                            <i class="ph ph-map-pin text-2xl"></i>
                            Delivery Address
                        </div>
                        <div class="space-y-1.5 text-secondary">
                            <div><span class="text-black font-semibold text-lg">{{ $order->customer->name }}</span></div>
                            <div class="flex items-center gap-2"><i class="ph ph-phone"></i> {{ $order->customer->phone }}</div>
                            @if($order->customer->email)
                            <div class="flex items-center gap-2"><i class="ph ph-envelope-simple"></i> {{ $order->customer->email }}</div>
                            @endif
                            <div class="pt-2 mt-2 border-t border-dashed border-gray-200">
                                {{ $order->shipping_address }}<br>
                                {{ $order->thana }}, {{ $order->city }}
                            </div>
                        </div>
                    </div>

                    {{-- Order Notes --}}
                    @if($order->notes)
                    <div class="border border-line rounded-2xl p-5 hover:shadow-md transition-shadow duration-300">
                        <div class="heading6 mb-2 flex items-center gap-2" style="color: #9A0002;">
                            <i class="ph ph-note-pencil text-2xl"></i>
                            Order Notes
                        </div>
                        <p class="text-secondary italic bg-gray-50 p-4 rounded-xl mt-3">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>

                {{-- Order Items --}}
                <div class="border border-line rounded-2xl p-5 mb-6 hover:shadow-md transition-shadow duration-300">
                    <div class="heading6 mb-6 flex items-center gap-2" style="color: #9A0002;">
                        <i class="ph ph-shopping-bag text-2xl"></i>
                        Items Ordered
                    </div>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center justify-between gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                            <div class="flex-grow">
                                <div class="text-title text-base font-semibold">{{ $item->product_name }}</div>
                                @if($item->color_name || $item->size_name)
                                <div class="text-secondary mt-1 text-sm bg-gray-50 inline-block px-2 py-0.5 rounded">
                                    {{ collect([$item->color_name, $item->size_name])->filter()->implode(' / ') }}
                                </div>
                                @endif
                                <div class="text-secondary mt-1.5 text-sm">Qty: <span class="font-bold text-black">{{ $item->quantity }}</span> &times; &#2547;{{ number_format($item->unit_price, 2) }}</div>
                            </div>
                            <div class="text-title font-bold text-lg flex-shrink-0">&#2547;{{ number_format($item->total_price, 2) }}</div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="mt-6 pt-5 border-t-2 border-dashed border-gray-200 space-y-3">
                        <div class="flex justify-between text-secondary">
                            <span>Subtotal</span>
                            <span class="font-medium text-black">&#2547;{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-secondary">
                            <span>Shipping</span>
                            <span class="text-green font-bold uppercase tracking-wide">Free</span>
                        </div>
                        <div class="flex justify-between text-title font-black text-xl pt-4 mt-2 border-t border-gray-200" style="color: #9A0002;">
                            <span>Total</span>
                            <span>&#2547;{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
                    <a href="{{ route('frontend.shop') }}" class="button-main text-center sm:w-56 py-3.5 text-base font-bold rounded-xl shadow-lg hover:-translate-y-1 transition-transform">
                        Continue Shopping
                    </a>
                    <a href="{{ route('frontend.home') }}" class="button-main text-center sm:w-56 py-3.5 text-base font-bold rounded-xl bg-white border-2 border-black text-black hover:bg-black hover:text-white transition-colors">
                        Back to Home
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
@if(session('order_success_toast'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.showToast === 'function') {
            setTimeout(function() {
                window.showToast('Order Placed Successfully!', 'success');
            }, 500);
        }
    });
</script>
@endif
@endpush

@endsection
