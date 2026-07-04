@extends('backend.layouts.master')

@section('title', 'Dashboard')

@section('content')

@php
    $currency = \App\Models\Setting::first()->currency_symbol ?? '৳';
@endphp

<!-- KPI Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6">
        <x-modern.card class="h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-white text-opacity-75 mb-1 fw-medium text-uppercase" style="font-size: 0.85rem;">Total Revenue</p>
                    <h3 class="fw-bold mb-0 text-white">{{ $currency }}{{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="flex-shrink-0 ms-3">
                    <div class="bg-white bg-opacity-25 rounded p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bx bx-dollar-circle" style="font-size: 32px;"></i>
                    </div>
                </div>
            </div>
        </x-modern.card>
    </div>

    <div class="col-xl-3 col-sm-6">
        <x-modern.card class="h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-white text-opacity-75 mb-1 fw-medium text-uppercase" style="font-size: 0.85rem;">Total Orders</p>
                    <h3 class="fw-bold mb-0 text-white">{{ number_format($totalOrders) }}</h3>
                </div>
                <div class="flex-shrink-0 ms-3">
                    <div class="bg-white bg-opacity-25 rounded p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bx bx-shopping-bag" style="font-size: 32px;"></i>
                    </div>
                </div>
            </div>
        </x-modern.card>
    </div>

    <div class="col-xl-3 col-sm-6">
        <x-modern.card class="h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-white text-opacity-75 mb-1 fw-medium text-uppercase" style="font-size: 0.85rem;">Pending Orders</p>
                    <h3 class="fw-bold mb-0 text-white">{{ number_format($pendingOrders) }}</h3>
                </div>
                <div class="flex-shrink-0 ms-3">
                    <div class="bg-white bg-opacity-25 rounded p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bx bx-time-five" style="font-size: 32px;"></i>
                    </div>
                </div>
            </div>
        </x-modern.card>
    </div>

    <div class="col-xl-3 col-sm-6">
        <x-modern.card class="h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #fccb90 0%, #d57eeb 100%); color: white;">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-white text-opacity-75 mb-1 fw-medium text-uppercase" style="font-size: 0.85rem;">Total Customers</p>
                    <h3 class="fw-bold mb-0 text-white">{{ number_format($totalCustomers) }}</h3>
                </div>
                <div class="flex-shrink-0 ms-3">
                    <div class="bg-white bg-opacity-25 rounded p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bx bx-group" style="font-size: 32px;"></i>
                    </div>
                </div>
            </div>
        </x-modern.card>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <x-modern.card title="Recent Orders" icon="bx bx-receipt">
            <x-slot name="actions">
                <x-modern.actions.button tag="a" href="{{ route('orders.online') }}" label="View All" size="sm" outline />
            </x-slot>

            <x-modern.table :headers="['Order ID', 'Customer', 'Date', 'Total', 'Status', 'Action']">
                @forelse($recentOrders as $order)
                <tr>
                    <td class="align-middle fw-bold">#{{ $order->order_number ?? $order->id }}</td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <span class="avatar-title rounded-circle bg-primary bg-opacity-10 text-primary">
                                    {{ substr($order->customer->fname ?? 'U', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0 font-size-14">{{ $order->customer->fname ?? 'Unknown' }} {{ $order->customer->lname ?? '' }}</h6>
                                <small class="text-muted">{{ $order->customer->phone ?? 'No Phone' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                    <td class="align-middle fw-bold text-primary">{{ $currency }}{{ number_format($order->total_amount, 2) }}</td>
                    <td class="align-middle">
                        <span class="badge" style="background-color: {{ $order->status->color_code ?? '#6c757d' }};">
                            {{ $order->status->name ?? 'Unknown' }}
                        </span>
                    </td>
                    <td class="align-middle">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-light border">
                            <i class="bx bx-show"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bx bx-inbox mb-2" style="font-size: 32px;"></i>
                        <p>No recent orders found.</p>
                    </td>
                </tr>
                @endforelse
            </x-modern.table>
        </x-modern.card>
    </div>

    <div class="col-xl-4">
        <x-modern.card title="Store Overview" icon="bx bx-store-alt">
            <div class="d-flex align-items-center p-3 border-bottom border-light hover-bg-light rounded transition-base">
                <div class="avatar-sm me-3 flex-shrink-0">
                    <span class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle" style="font-size: 20px;">
                        <i class="bx bx-box"></i>
                    </span>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <p class="text-muted mb-0 font-size-13 text-truncate">Active Products</p>
                    <h5 class="mb-0 fw-bold">{{ number_format($totalProducts) }}</h5>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-link"><i class="bx bx-right-arrow-alt"></i></a>
                </div>
            </div>

            <div class="d-flex align-items-center p-3 border-bottom border-light hover-bg-light rounded transition-base">
                <div class="avatar-sm me-3 flex-shrink-0">
                    <span class="avatar-title bg-info bg-opacity-10 text-info rounded-circle" style="font-size: 20px;">
                        <i class="bx bx-image"></i>
                    </span>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <p class="text-muted mb-0 font-size-13 text-truncate">Promotional Banners</p>
                    <h5 class="mb-0 fw-bold">{{ \App\Models\Banner::count() }}</h5>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('banners.index') }}" class="btn btn-sm btn-link"><i class="bx bx-right-arrow-alt"></i></a>
                </div>
            </div>

            <div class="d-flex align-items-center p-3 hover-bg-light rounded transition-base">
                <div class="avatar-sm me-3 flex-shrink-0">
                    <span class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle" style="font-size: 20px;">
                        <i class="bx bx-file"></i>
                    </span>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <p class="text-muted mb-0 font-size-13 text-truncate">Dynamic Pages</p>
                    <h5 class="mb-0 fw-bold">{{ \App\Models\Page::count() }}</h5>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('pages.index') }}" class="btn btn-sm btn-link"><i class="bx bx-right-arrow-alt"></i></a>
                </div>
            </div>
        </x-modern.card>
    </div>
</div>

<style>
.transition-base { transition: all 0.2s ease-in-out; }
.hover-bg-light:hover { background-color: #f8f9fa !important; }
</style>
@endsection