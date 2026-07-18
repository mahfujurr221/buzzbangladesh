@extends('frontend.layouts.master')

@section('content')
<div class="breadcrumb-block style-shared">
                <div class="breadcrumb-main bg-linear overflow-hidden">
                    <div class="container lg:pt-[134px] pt-24 pb-10 relative">
                        <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                            <div class="text-content">
                                <div class="heading2 text-center">Checkout</div>
                                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                                    <a href="{{ route('frontend.home') }}">Homepage</a>
                                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                                    <div class="text-secondary2 capitalize">Checkout</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="checkout-block md:py-20 py-10">
            <div class="container">
                <div class="content-main flex max-lg:flex-col-reverse gap-y-10 justify-between">
                    <div class="left lg:w-1/2">
                        <div class="login bg-surface py-3 px-4 flex justify-between rounded-lg">
                            <div class="left flex items-center"><span class="text-on-surface-variant1 pr-4">Already have an account? </span><span class="text-button text-on-surface hover-underline cursor-pointer hover:underline">Login</span></div>
                            <div class="right"><i class="ph ph-caret-down fs-20 cursor-pointer"></i></div>
                        </div>
                        <div class="form-login-block mt-3">
                            <form class="p-5 border border-line rounded-lg">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="email">
                                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg" id="username" type="email" placeholder="Username or email" required />
                                    </div>
                                    <div class="pass">
                                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg" id="password" type="password" placeholder="Password" required />
                                    </div>
                                </div>
                                <div class="block-button mt-3">
                                    <button class="button-main button-blue-hover">Login</button>
                                </div>
                            </form>
                        </div>
                        <div class="information mt-5">
                            <div class="heading5">Information</div>
                            <div class="form-checkout mt-5">
                                <form>
                                    <div class="grid sm:grid-cols-2 gap-4 gap-y-5 flex-wrap">
                                        <div class="col-span-full">
                                            <input class="border-line px-4 py-3 w-full rounded-lg" id="fullName" name="name" type="text" placeholder="Full Name *" required />
                                        </div>
                                        <div class="">
                                            <input class="border-line px-4 py-3 w-full rounded-lg" id="phoneNumber" name="phone" type="text" placeholder="Phone Number *" required />
                                        </div>
                                        <div class="">
                                            <input class="border-line px-4 py-3 w-full rounded-lg" id="email" name="email" type="email" placeholder="Email Address *" required />
                                        </div>
                                        <div class="">
                                            <input class="border-line px-4 py-3 w-full rounded-lg" id="city" name="city" type="text" placeholder="City *" required />
                                        </div>
                                        <div class="">
                                            <input class="border-line px-4 py-3 w-full rounded-lg" id="thana" name="thana" type="text" placeholder="Thana *" required />
                                        </div>
                                        <div class="col-span-full">
                                            <textarea class="border border-line px-4 py-3 w-full rounded-lg" id="address" name="address" placeholder="Full Address *" required></textarea>
                                        </div>
                                    </div>
                                    <div class="payment-block md:mt-10 mt-6">
                                        <div class="heading5">Choose payment Option:</div>
                                        <div class="list-payment mt-5">
                                            <div class="type bg-surface p-5 border border-line rounded-lg mt-5">
                                                <input class="cursor-pointer" type="radio" id="delivery" name="payment" value="cod" checked />
                                                <label class="text-button pl-2 cursor-pointer" for="delivery">Cash on delivery (COD)</label>
                                                <div class="infor">
                                                    <div class="text-on-surface-variant1 pt-4">Pay with cash upon delivery.</div>
                                                </div>
                                            </div>
                                            <div class="type bg-surface p-5 border border-line rounded-lg mt-5">
                                                <input class="cursor-pointer" type="radio" id="online" name="payment" value="online" />
                                                <label class="text-button pl-2 cursor-pointer" for="online">Online Payment</label>
                                                <div class="infor">
                                                    <div class="text-on-surface-variant1 pt-4">Pay securely using your credit/debit card, Mobile Banking (bKash, Nagad), etc.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="block-button md:mt-10 mt-6">
                                        <button class="button-main w-full">Payment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="right lg:w-5/12">
                        <div class="checkout-block">
                            <div class="heading5 pb-3">Your Order</div>
                            <div class="list-product-checkout"></div>
                            <div class="discount-block py-5 flex justify-between border-b border-line">
                                <div class="text-title">Discounts</div>
                                <div class="text-title">-$<span class="discount">0</span><span>.00</span></div>
                            </div>
                            <div class="ship-block py-5 flex justify-between border-b border-line">
                                <div class="text-title">Shipping</div>
                                <div class="text-title">Free</div>
                            </div>
                            <div class="total-cart-block pt-5 flex justify-between">
                                <div class="heading5">Total</div>
                                <div class="heading5 total-cart">$0.00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection