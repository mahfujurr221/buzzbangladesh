@extends('backend.layouts.master')

@section('title', 'Online Orders')

@section('content')

@can('list-online-order')
<x-modern.filter title="Filter Orders" icon="bx bx-search-alt" :resetUrl="route('orders.online')"
    :expanded="request()->anyFilled(['order_id', 'customer_info', 'product_name'])" class="mb-4">
    <div class="col-md-4">
        <x-modern.input label="Order #" name="order_id" placeholder="Search by Order ID" :value="request('order_id')"
            icon="bx bx-hash" />
    </div>
    <div class="col-md-4">
        <x-modern.input label="Customer Info" name="customer_info" placeholder="Name or Phone" :value="request('customer_info')"
            icon="bx bx-user" />
    </div>
    <div class="col-md-4">
        <x-modern.input label="Product Name" name="product_name" placeholder="Search product" :value="request('product_name')"
            icon="bx bx-box" />
    </div>
</x-modern.filter>

<x-modern.card title="Online Orders Overview" icon="bx bx-shopping-bag">

    @include('backend.pages.order.partials.order_table')

</x-modern.card>
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
