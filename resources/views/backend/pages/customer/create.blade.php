@extends('backend.layouts.master')

@section('title', 'Add Customer')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <x-modern.card title="Add New Customer">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Full Name" name="name" placeholder="e.g. John Doe" required="true" icon="bx bx-user" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Phone Number" name="phone" placeholder="e.g. 01700000000" required="true" icon="bx bx-phone" help="Must be unique." />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <x-modern.input label="Email Address (Optional)" name="email" type="email" placeholder="e.g. john@example.com" icon="bx bx-envelope" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="City" name="city" placeholder="e.g. Dhaka" icon="bx bx-building" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Thana / Area" name="thana" placeholder="e.g. Dhanmondi" icon="bx bx-map-pin" />
                    </div>
                </div>

                <div class="mb-4">
                    <x-modern.input label="Full Address (Optional)" name="full_address" type="textarea" placeholder="Detailed shipping or billing address..." icon="bx bx-map" />
                </div>

                <div class="d-flex justify-content-between">
                    <x-modern.actions.button tag="a" href="{{ route('customers.index') }}" actionType="back" />
                    <x-modern.actions.button type="submit" actionType="save" label="Save Customer" />
                </div>
            </form>
        </x-modern.card>
    </div>
</div>
@endsection
