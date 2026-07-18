<div class="modal-wishlist-block">
            <div class="modal-wishlist-main py-6">
                <div class="heading px-6 pb-3 flex items-center justify-between relative">
                    <div class="heading5">Wishlist</div>
                    <div class="close-btn absolute right-6 top-0 w-6 h-6 rounded-full bg-surface flex items-center justify-center duration-300 cursor-pointer hover:bg-black hover:text-white">
                        <i class="ph ph-x text-sm"></i>
                    </div>
                </div>
                <div class="list-product px-6"></div>
                <div class="footer-modal p-6 border-t bg-white border-line absolute bottom-0 left-0 w-full text-center">
                    <a href="wishlist.html" class="button-main w-full text-center uppercase"> View All Wish List</a>
                    <div class="text-button-uppercase continue mt-4 text-center has-line-before cursor-pointer inline-block">Or continue shopping</div>
                </div>
            </div>
        </div>

        <div class="modal-cart-block">
            <div class="modal-cart-main flex">
                <div class="left w-1/2 border-r border-line py-6 max-md:hidden">
                    <div class="heading5 px-6 pb-3">You May Also Like</div>
                    <div class="list px-6">
                        <!-- Add dynamic product recommendations here later -->
                    </div>
                </div>
                <div class="right cart-block md:w-1/2 w-full py-6 relative overflow-hidden">
                    <div class="heading px-6 pb-3 flex items-center justify-between relative">
                        <div class="heading5">Shopping Cart</div>
                        <div class="close-btn absolute right-6 top-0 w-6 h-6 rounded-full bg-surface flex items-center justify-center duration-300 cursor-pointer hover:bg-black hover:text-white">
                            <i class="ph ph-x text-sm"></i>
                        </div>
                    </div>
                    <div class="time countdown-cart px-6">
                        <div class="flex items-center gap-3 px-5 py-3 bg-green rounded-lg">
                            <p class="text-3xl">🔥</p>
                            <div class="caption1">
                                Your cart will expire in <span class="text-red caption1 font-semibold"><span class="minute">04</span>:<span class="second">59</span></span> minutes!<br />
                                Please checkout now before your items sell out!
                            </div>
                        </div>
                    </div>
                    <div class="heading banner mt-3 px-6">
                        <div class="text">
                            Buy <span class="text-button"> $<span class="more-price">150</span>.00 </span>
                            <span>more to get </span>
                            <span class="text-button">freeship</span>
                        </div>
                        <div class="tow-bar-block mt-3">
                            <div class="progress-line"></div>
                        </div>
                    </div>
                    <div class="list-product px-6"></div>
                    <div class="footer-modal bg-white absolute bottom-0 left-0 w-full">
                        <div class="flex items-center justify-center lg:gap-14 gap-8 px-6 py-4 border-b border-line">
                            <div class="note-btn item flex items-center gap-3 cursor-pointer">
                                <i class="ph ph-note-pencil text-xl"></i>
                                <div class="caption1">Note</div>
                            </div>
                            <div class="shipping-btn item flex items-center gap-3 cursor-pointer">
                                <i class="ph ph-truck text-xl"></i>
                                <div class="caption1">Shipping</div>
                            </div>
                            <div class="coupon-btn item flex items-center gap-3 cursor-pointer">
                                <i class="ph ph-tag text-xl"></i>
                                <div class="caption1">Coupon</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-6 px-6">
                            <div class="heading5">Subtotal</div>
                            <div class="heading5 total-cart">$0.00</div>
                        </div>
                        <div class="block-button text-center p-6">
                            <div class="flex items-center gap-4">
                                <a href="/cart" class="button-main basis-1/2 bg-white border border-black text-black text-center uppercase"> View cart </a>
                                <a href="/checkout" class="button-main basis-1/2 text-center uppercase"> Check Out </a>
                            </div>
                            <div class="text-button-uppercase continue mt-4 text-center has-line-before cursor-pointer inline-block">Or continue shopping</div>
                        </div>
                        <div class="tab-item note-block">
                            <div class="px-6 py-4 border-b border-line">
                                <div class="item flex items-center gap-3 cursor-pointer">
                                    <i class="ph ph-note-pencil text-xl"></i>
                                    <div class="caption1">Note</div>
                                </div>
                            </div>
                            <div class="form pt-4 px-6">
                                <textarea name="form-note" id="form-note" rows="4" placeholder="Add special instructions for your order..." class="caption1 py-3 px-4 bg-surface border-line rounded-md w-full"></textarea>
                            </div>
                            <div class="block-button text-center pt-4 px-6 pb-6">
                                <div class="button-main w-full text-center">Save</div>
                                <div class="cancel-btn text-button-uppercase mt-4 text-center has-line-before cursor-pointer inline-block">Cancel</div>
                            </div>
                        </div>
                        <div class="tab-item shipping-block">
                            <div class="px-6 py-4 border-b border-line">
                                <div class="item flex items-center gap-3 cursor-pointer">
                                    <i class="ph ph-truck text-xl"></i>
                                    <div class="caption1">Estimate shipping rates</div>
                                </div>
                            </div>
                            <div class="form pt-4 px-6">
                                <div class="">
                                    <label for="select-country" class="caption1 text-secondary">Country/region</label>
                                    <div class="select-block relative mt-2">
                                        <select id="select-country" name="select-country" class="w-full py-3 pl-5 rounded-xl bg-white border border-line">
                                            <option value="Country/region">Country/region</option>
                                            <option value="France">France</option>
                                            <option value="Spain">Spain</option>
                                            <option value="UK">UK</option>
                                            <option value="USA">USA</option>
                                        </select>
                                        <i class="ph ph-caret-down text-xs absolute top-1/2 -translate-y-1/2 md:right-5 right-2"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="select-state" class="caption1 text-secondary">State</label>
                                    <div class="select-block relative mt-2">
                                        <select id="select-state" name="select-state" class="w-full py-3 pl-5 rounded-xl bg-white border border-line">
                                            <option value="State">State</option>
                                            <option value="Paris">Paris</option>
                                            <option value="Madrid">Madrid</option>
                                            <option value="London">London</option>
                                            <option value="New York">New York</option>
                                        </select>
                                        <i class="ph ph-caret-down text-xs absolute top-1/2 -translate-y-1/2 md:right-5 right-2"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="select-code" class="caption1 text-secondary">Postal/Zip Code</label>
                                    <input class="border-line px-5 py-3 w-full rounded-xl mt-3" id="select-code" type="text" placeholder="Postal/Zip Code" />
                                </div>
                            </div>
                            <div class="block-button text-center pt-4 px-6 pb-6">
                                <div class="button-main w-full text-center">Calculator</div>
                                <div class="cancel-btn text-button-uppercase mt-4 text-center has-line-before cursor-pointer inline-block">Cancel</div>
                            </div>
                        </div>
                        <div class="tab-item coupon-block">
                            <div class="px-6 py-4 border-b border-line">
                                <div class="item flex items-center gap-3 cursor-pointer">
                                    <i class="ph ph-tag text-xl"></i>
                                    <div class="caption1">Add A Coupon Code</div>
                                </div>
                            </div>
                            <div class="form pt-4 px-6">
                                <div class="">
                                    <label for="select-discount" class="caption1 text-secondary">Enter Code</label>
                                    <input class="border-line px-5 py-3 w-full rounded-xl mt-3" id="select-discount" type="text" placeholder="Discount code" />
                                </div>
                            </div>
                            <div class="block-button text-center pt-4 px-6 pb-6">
                                <div class="button-main w-full text-center">Apply</div>
                                <div class="cancel-btn text-button-uppercase mt-4 text-center has-line-before cursor-pointer inline-block">Cancel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-compare-block">
            <div class="modal-compare-main py-6">
                <div class="close-btn absolute 2xl:right-6 right-4 2xl:top-6 md:-top-4 top-3 lg:w-10 w-6 lg:h-10 h-6 rounded-full bg-surface flex items-center justify-center duration-300 cursor-pointer hover:bg-black hover:text-white">
                    <i class="ph ph-x body1"></i>
                </div>
                <div class="container h-full flex items-center w-full">
                    <div class="content-main flex items-center justify-between xl:gap-10 gap-6 w-full max-md:flex-wrap">
                        <div class="heading5 flex-shrink-0 max-md:w-full">Compare <br class="max-md:hidden" />Products</div>
                        <div class="list-product flex items-center w-full gap-4"></div>
                        <div class="block-button flex flex-col gap-4 flex-shrink-0">
                            <a href="/compare" class="button-main whitespace-nowrap"> Compare Products</a>
                            <div class="button-main clear whitespace-nowrap border border-black bg-white text-black">Clear All Products</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
