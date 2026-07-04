@extends('backend.layouts.master')

@section('title', 'Online Orders')

@section('content')

@can('list-online-order')
<x-modern.filter title="Filter Orders" icon="bx bx-search-alt" :resetUrl="route('orders.online')"
    :expanded="request()->anyFilled(['search'])" class="mb-4">
    <div class="col-md-6">
        <x-modern.input label="Search Keyword" name="search" placeholder="Search by Order #, Customer Name, or Phone..." :value="request('search')"
            icon="bx bx-search" />
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
