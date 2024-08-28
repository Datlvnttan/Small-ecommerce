@extends('product::layouts.master')
@section('css')
    <!-- SPECIFIC CSS -->
    <link href="{{ asset('modules/cart/css/cart.css') }}" rel="stylesheet">
@endsection
@section('content')
    <main class="bg_gray" id="main-cart">
        <div class="container margin_30">
            <div class="page_header">
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="{{route('web.home.index')}}">Home</a></li>
                        {{-- <li><a href="#">Category</a></li> --}}
                        <li>Cart</li>
                    </ul>
                </div>
                <h1>Cart page</h1>
            </div>
            <!-- /page_header -->
            <table class="table table-striped cart-list">
                <thead>
                    <tr>
                        <th>
                            Product
                        </th>
                        <th>
                            Price
                        </th>
                        <th>
                            Quantity
                        </th>
                        <th>
                            Subtotal
                        </th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody id="cart_data">
                    <tr>
                        <td>
                            <div class="thumb_cart">
                                <img src="{{ asset('img/products/product_placeholder_square_small.jpg') }}"
                                    data-src="img/products/shoes/1.jpg" class="lazy" alt="Image">
                            </div>
                            <h5 class="item_cart">Armor Air x Fear</h5>
                            <span class="item_cart item_cart_option">option 1 2 3 4</span>
                        </td>
                        <td>
                            <strong>$140.00</strong>
                        </td>
                        <td>
                            <div class="numbers-row">
                                <input type="text" value="1" id="quantity_1" class="qty2" name="quantity_1">
                                <div class="inc button_inc">+</div>
                                <div class="dec button_inc">-</div>
                            </div>
                        </td>
                        <td>
                            <strong>$140.00</strong>
                        </td>
                        <td class="options">
                            <a href="#"><i class="ti-trash"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="thumb_cart">
                                <img src="img/products/product_placeholder_square_small.jpg"
                                    data-src="img/products/shoes/2.jpg" class="lazy" alt="Image">
                            </div>
                            <span class="item_cart">Armor Okwahn II</span>
                        </td>
                        <td>
                            <strong>$110.00</strong>
                        </td>
                        <td>
                            <div class="numbers-row">
                                <input type="text" value="1" id="quantity_2" class="qty2" name="quantity_2">
                                <div class="inc button_inc">+</div>
                                <div class="dec button_inc">-</div>
                            </div>
                        </td>
                        <td>
                            <strong>$110.00</strong>
                        </td>
                        <td class="options">
                            <a href="#"><i class="ti-trash"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="thumb_cart">
                                <img src="img/products/product_placeholder_square_small.jpg"
                                    data-src="img/products/shoes/3.jpg" class="lazy" alt="Image">
                            </div>
                            <span class="item_cart">Armor Air Wildwood ACG</span>
                        </td>
                        <td>
                            <strong>$90.00</strong>
                        </td>

                        <td>
                            <div class="numbers-row">
                                <input type="text" value="1" id="quantity_3" class="qty2" name="quantity_3">
                                <div class="inc button_inc">+</div>
                                <div class="dec button_inc">-</div>
                            </div>
                        </td>
                        <td>
                            <strong>$90.00</strong>
                        </td>
                        <td class="options">
                            <a href="#"><i class="ti-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="row add_top_30 flex-sm-row-reverse cart_actions">
                {{-- <div class="col-sm-4 text-end">
                        <button type="button" class="btn_1 gray">Update Cart</button>
                    </div> --}}
                <div class="col-lg-6">
                    <div class="apply-coupon">

                        {{-- <div class="form-group">
                                <div class="row g-2">
                                    <div class="col-md-6"><input type="text" name="coupon-code" value="" placeholder="Promo code" class="form-control"></div>
                                    <div class="col-md-4"><button type="button" class="btn_1 outline">Apply Coupon</button></div>
                                </div>
                            </div> --}}
                    </div>
                </div>
            </div>
            <!-- /cart_actions -->

        </div>
        <!-- /container -->

        <div class="box_cart">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-xl-7 col-lg-8 col-md-9">
                        <ul>
                            <li id="subtotal">
                                <span>Subtotal</span> $240.00
                            </li>
                            <li id="shipping-fee">
                                <span>Shipping Fee</span> $7.00
                            </li>
                            <li id="sale">
                                <span>Sale</span> -$3.00 (shipping)
                            </li>
                            <li>
                                <div class="row" id="shipping-items">
                                    <div class="col-4 p-1">
                                        <div class="justify-content-center">
                                            <div class="border border-dark shipping-item  p-2 ">
                                                <center><b>Nhanh</b></center>
                                                <center><b class="shipping-expense-new">151.05 USD save 33%</b></center>
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
                            </li>
                            <li id="total">
                                <span>Total</span> $247.00
                            </li>
                        </ul>

                        <button class="btn_1 full-width cart" id="btn_checkout">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /box_cart -->

    </main>
    <!--/main-->
@endsection
@section('js')
    {{-- <!-- SPECIFIC SCRIPTS -->
    <script src="{{ asset('library/js/carousel_with_thumbs.js') }}"></script> --}}

    <script src="{{ asset('modules/cart/js/cart.js') }}"></script>
@endsection
