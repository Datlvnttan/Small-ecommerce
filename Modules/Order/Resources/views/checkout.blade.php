@php
    $user = Auth::user();
@endphp
@extends('layouts.app')
@section('css')
    <!-- SPECIFIC CSS -->
    <link href="{{ asset('modules/order/css/checkout.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/user/css/address.css') }}">
@endsection
@section('master')
    <main class="bg_gray">


        <div class="container margin_30">
            <div class="page_header">
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="{{ route('web.home.index') }}">Home</a></li>
                        <li><a href="{{ route('web.cart.index') }}">Cart</a></li>
                        <li>Checkout</li>
                    </ul>
                </div>
                <h1>Checkout</h1>

            </div>
            <!-- /page_header -->
            <div class="row">
                <div class="col-lg-7">
                    <div class="row">
                        <form id="form-order-information">
                            <div class="step first">
                                <h3>Discount Coupon</h3>
                                <div class="tab-content checkout">
                                    <div class="form-group">

                                        <div class="row no-gutters">
                                            <label for="coupon_code">Coupon Code</label>
                                            <div class="col-9 form-group pr-1">
                                                <input type="text" id="coupon_code" name="couponCode"
                                                    class="form-control"
                                                    placeholder="Please type your coupon code the box below">
                                            </div>
                                            <div class="col-3 form-group pl-1">
                                                <a class="btn btn-secondary" id="btn_add_coupon_code">Add</a>
                                                <a class="btn btn-dark" id="btn_remove_coupon_code">Remove</a>

                                            </div>
                                            <span id="error-discount-coupon">-</span>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="note">Note</label>
                                        <input type="text" id="note" name="note" class="form-control"
                                            placeholder="Note">
                                    </div>
                                </div>
                            </div>
                            <!-- /coupon code -->
                            <div class="step first">
                                <h3>Delivery and Billing address</h3>
                                <h6><b>Delivery address</b></h6>
                                <ul class="nav nav-tabs" id="tab_checkout" role="tablist">
                                    {{-- <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#tab_1"
                                        role="tab" aria-controls="tab_1" aria-selected="true">Shipping Infomation</a>
                                </li> --}}
                                    {{-- <li class="nav-item">
									<a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#tab_2" role="tab"
										aria-controls="tab_2" aria-selected="false">Billing address</a>
								</li> --}}
                                </ul>
                                <div class="tab-content checkout">
                                    {{-- @if (isset($user) || $user->isNotDeliveryAddress()) --}}
                                    @if (isset($user))
                                        @include('order::components.checkout.checkout-address-member')
                                    @else
                                        @include('order::components.checkout.checkout-address-guest')
                                    @endif

                                    <hr>
                                    <h6><b>Shipping method</b></h6>
                                    <div class="row" id="shipping-items">
                                        <div class="col-4">
                                            <div class="justify-content-center">
                                                <div class="border border-dark shipping-item  p-2">
                                                    <input type="radio" name="shipping_method" class="float-right">
                                                    <center><b>Nhanh</b></center>
                                                    <center><b class="shipping-expense-new">151.05 USD save 33%</b>
                                                    </center>
                                                    <center>
                                                        <p class="shipping-expense-old">256.34 USD</p>
                                                    </center>
                                                    <center>
                                                        <b class="shipping-expense-delivery-time">About 3 days</b>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="justify-content-center">
                                                <div class="border border-dark shipping-item  p-2">
                                                    <input type="radio" name="shipping_method" class="float-right">
                                                    <center><b>Nhanh</b></center>
                                                    <center><b class="shipping-expense-new">151.05 USD save 33%</b>
                                                    </center>
                                                    <center>
                                                        <p class="shipping-expense-old">256.34 USD</p>
                                                    </center>
                                                    <center>
                                                        <b class="shipping-expense-delivery-time">About 3 days</b>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="justify-content-center">
                                                <div class="border border-dark shipping-item  p-2">
                                                    <input type="radio" name="shipping_method" class="float-right">
                                                    <center><b>Nhanh</b></center>
                                                    <center><b class="shipping-expense-new">151.05 USD save 33%</b>
                                                    </center>
                                                    <center>
                                                        <p class="shipping-expense-old">256.34 USD</p>
                                                    </center>
                                                    <center>
                                                        <b class="shipping-expense-delivery-time">About 3 days</b>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <!--shipping-->
                                    <!-- /tab_1 -->
                                    <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2"
                                        style="position: relative;">

                                    </div>
                                    <!-- /tab_2 -->
                                </div>
                            </div>
                            <!-- /step -->
                            <div class="step middle payments">
                                <h3>Payment and Shipping</h3>
                                <ul>
                                    <li>
                                        <label class="container_radio">Credit Card<a href="#0" class="info"
                                                data-bs-toggle="modal" data-bs-target="#payments_method"></a>
                                            <input type="radio" name="paymentMethod" value="Credit Card" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="container_radio">Paypal<a href="#0" class="info"
                                                data-bs-toggle="modal" data-bs-target="#payments_method"></a>
                                            <input type="radio" name="paymentMethod" value="Paypal">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="container_radio">Cash on delivery<a href="#0" class="info"
                                                data-bs-toggle="modal" data-bs-target="#payments_method"></a>
                                            <input type="radio" name="paymentMethod" value="Cash on delivery">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="container_radio">Bank Transfer<a href="#0" class="info"
                                                data-bs-toggle="modal" data-bs-target="#payments_method"></a>
                                            <input type="radio" name="paymentMethod" value="Bank Transfer">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </ul>
                                <div class="payment_info d-none d-sm-block">
                                    <figure><img src="img/cards_all.svg" alt=""></figure>
                                    <p>Sensibus reformidans interpretaris sit ne, nec errem nostrum et, te nec meliore
                                        philosophia.
                                        At vix quidam periculis. Solet tritani ad pri, no iisque definitiones sea.</p>
                                </div>

                            </div>
                        </form>
                        <!-- /step -->
                    </div>
                </div>
                <!-- /col -->
                <div class="col-lg-5">
                    <div class="step last sticky-order-summary">
                        <h3>Order Summary</h3>
                        <div class="box_general summary">
                            <ul id="order-detail">
                                <li class="clearfix">
                                    <div class="product-img" style="background-image: url();"></div>
                                    <em><strong>Armor Air X Fear</strong><br>options 1 2 3 4 5<br>x 1</em>
                                    <span>$145.00</span>
                                </li>
                                <li class="clearfix">
                                    <em>2x Armor Air Zoom Alpha</em> <span>$115.00</span>
                                </li>
                            </ul>
                            <ul>
                                <li class="clearfix"><em><strong>Subtotal</strong></em> <span
                                        id="subtotal">$450.00</span></li>
                                <li class="clearfix"><em><strong>Shipping Fee</strong></em> <span
                                        id="shipping-fee">$0</span></li>
                                <li class="clearfix" id="sale"><em><strong>Sale</strong></em> <span>-
                                        $0(shipping)</span><span>- $0(shipping)</span></li>

                            </ul>
                            <div class="total clearfix">TOTAL <span id="total">$450.00</span></div>


                            <button id="btn_confirm" class="btn_1 full-width">Confirm and Pay</button>
                        </div>
                        <!-- /box_general -->
                    </div>
                    <!-- /step -->
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </main>
@endsection
@section('js')
    {{-- <!-- SPECIFIC SCRIPTS -->
    <script src="{{ asset('library/js/carousel_with_thumbs.js') }}"></script> --}}

    <script src="{{ asset('modules/order/js/checkout.js') }}"></script>
    <script>
        // Other address Panel
        $('#other_addr input').on("change", function() {
            if (this.checked)
                $('#other_addr_c').fadeIn('fast');
            else
                $('#other_addr_c').fadeOut('fast');
        });
    </script>
@endsection
