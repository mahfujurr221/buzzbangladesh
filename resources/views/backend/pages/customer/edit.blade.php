@extends('backend.layouts.master')

@section('title', 'Edit Customer')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <x-modern.card title="Edit Customer: {{ $customer->name }}">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Full Name" name="name" value="{{ $customer->name }}" placeholder="e.g. John Doe" required="true" icon="bx bx-user" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Phone Number" name="phone" value="{{ $customer->phone }}" placeholder="e.g. 01700000000" required="true" icon="bx bx-phone" help="Must be unique." />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <x-modern.input label="Email Address (Optional)" name="email" value="{{ $customer->email }}" type="email" placeholder="e.g. john@example.com" icon="bx bx-envelope" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="City" name="city" value="{{ $customer->city }}" placeholder="e.g. Dhaka" icon="bx bx-building" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Thana / Area" name="thana" value="{{ $customer->thana }}" placeholder="e.g. Dhanmondi" icon="bx bx-map-pin" />
                    </div>
                </div>

                <div class="mb-4">
                    <x-modern.input label="Full Address (Optional)" name="full_address" value="{{ $customer->full_address }}" type="textarea" placeholder="Detailed shipping or billing address..." icon="bx bx-map" />
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Update Customer
                    </button>
                </div>
            </form>
        </x-modern.card>
    </div>
</div>
@endsection
