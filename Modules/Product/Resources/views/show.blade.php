@extends('product::layouts.master')
@section('css')
    <!-- SPECIFIC CSS -->
    <link href="{{ asset('modules/product/css/product_page.css') }}" rel="stylesheet">
@endsection
@section('content')
    <main>
        <div class="container margin_30">
            <div class="countdown_inner">FLASH SALE extra <span id="flash_sale_discount_percent"></span>% This offer ends in <div data-countdown="2025/05/15" id="flash_sale_end_time" class="countdown"></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="all">
                        <div class="slider">
                            <div class="owl-carousel owl-theme main" id="product_image_main">
                                {{-- <div
                                    style="background-image: url({{ asset('img/products/shoes/1.jpg') }});"class="item-box">
                                </div>
                                <div style="background-image: url({{ asset('img/products/shoes/1.jpg') }});"
                                    class="item-box"></div>
                                <div style="background-image: url({{ asset('img/products/shoes/1.jpg') }});"
                                    class="item-box"></div>
                                <div style="background-image: url({{ asset('img/products/shoes/1.jpg') }});"
                                    class="item-box"></div>
                                <div style="background-image: url({{ asset('img/products/shoes/1.jpg') }});"
                                    class="item-box"></div>
                                <div style="background-image: url({{ asset('img/products/shoes/1.jpg') }});"
                                    class="item-box"></div> --}}
                            </div>
                            <div class="left nonl"><i class="ti-angle-left"></i></div>
                            <div class="right"><i class="ti-angle-right"></i></div>
                        </div>
                        <div class="slider-two">
                            <div class="owl-carousel owl-theme thumbs" id="product_image_thumbs">
                                {{-- <div style="background-image: url({{ asset('img/products/shoes/1.jpg') }});"
                                    class="item active"></div>
                                <div style="background-image: url({{ asset('img/products/shoes/2.jpg') }});" class="item">
                                </div>
                                <div style="background-image: url({{ asset('img/products/shoes/3.jpg') }});" class="item">
                                </div>
                                <div style="background-image: url({{ asset('img/products/shoes/4.jpg') }});"
                                    class="item">
                                </div>
                                <div style="background-image: url({{ asset('img/products/shoes/5.jpg') }});"
                                    class="item">
                                </div>
                                <div style="background-image: url({{ asset('img/products/shoes/6.jpg') }});"
                                    class="item">
                                </div> --}}
                            </div>
                            <div class="left-t nonl-t"></div>
                            <div class="right-t"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="breadcrumbs">
                        <ul id="category_hierarchy">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Category</a></li>
                            <li>Page active</li>
                        </ul>
                    </div>
                    <!-- /page_header -->
                    <div class="prod_info">
                        <h1 id="product_name">Armor Air X Fear</h1>
                        <span class="rating">
                            <span id="product_average_rating">
                                <i class="icon-star voted"></i>
                                <i class="icon-star voted"></i>
                                <i class="icon-star voted"></i>
                                <i class="icon-star voted"></i>
                                <i class="icon-star"></i>
                            </span>
                            <em class="product_total_rating">4 reviews</em>
                        </span>
                        <p><small>------------------------</small>
                            <br>
                            
                        </p>
                        <div class="prod_options">
                            <div id="product_attribute">
                                <div class="row" id="attribute-title">
                                    <label class="col-lg-5  col-6 pt-0"><strong
                                            style="color: crimson">Attribute:</strong></label>
                                    <div class="col-lg-7 col-6 colors">
                                        <span>Choose optipn:</span>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                <label class="col-xl-5 col-lg-5  col-md-6 col-6 pt-0"><strong>Color</strong></label>
                                <div class="col-xl-4 col-lg-5 col-md-6 col-6 colors">
                                    <ul>
                                        <li><a href="#0" class="color color_1 active"></a></li>
                                        <li><a href="#0" class="color color_2"></a></li>
                                        <li><a href="#0" class="color color_3"></a></li>
                                        <li><a href="#0" class="color color_4"></a></li>
                                    </ul>
                                </div>
                            </div> --}}
                                {{-- <div class="row">
                                    <label class="col-xl-5 col-lg-5 col-md-6 col-6"><strong>Size</strong> - Size Guide <a
                                            href="#0" data-bs-toggle="modal" data-bs-target="#size-modal"><i
                                                class="ti-help-alt"></i></a></label>
                                    <div class="col-xl-7 col-lg-7 col-md-6 col-6">
                                        <div class="custom-select-form">
                                            <select class="wide">
                                                <option value="" selected>Small (S)</option>
                                                <option value="">M</option>
                                                <option value=" ">L</option>
                                                <option value=" ">XL</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            <hr>
                            <div class="row">
                                <label class="col-xl-5 col-lg-5  col-md-6 col-6"><strong>Quantity (<span
                                            id="product_total_quantity"></span>)</strong></label>
                                <div class="col-xl-4 col-lg-5 col-md-6 col-6">
                                    <div class="numbers-row">
                                        <input type="text" value="1" id="quantity_1" class="qty2"
                                            name="quantity_1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-6">
                                <div class="price_main">
                                    <span class="new_price" id="product_price_range_new">$148.00</span>
                                    <span class="percentage" id="percentage">-20%</span>
                                    <span class="old_price" id="product_price_range_old">$160.00</span>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 " id="operation-product">
                                <div class="btn_add_product_to_cart"><button class="btn_1 w-100" id="btn_add_to_cart">Add to
                                        Cart</button>
                                </div>
                                {{-- <div class="buy_now btn_add_product_to_cart"><button class="btn_1 btn_2 w-100"
                                        id="btn_buy_now">Buy
                                        now</button></div> --}}
                            </div>

                        </div>
                    </div>
                    <!-- /prod_info -->
                    <div class="product_actions">
                        <ul>
                            <li>
                                <a id="add_to_favorite"><i class="ti-heart"></i><span>Add to favorites</span></a>
                            </li>
                            <li>
                                <a href="#"><i class="ti-control-shuffle"></i><span>Add to Compare</span></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /product_actions -->
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->

        <div class="tabs_product">
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a id="tab-A" href="#pane-A" class="nav-link active" data-bs-toggle="tab"
                            role="tab">Description</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-B" href="#pane-B" class="nav-link" data-bs-toggle="tab" role="tab">Reviews</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /tabs_product -->
        <div class="tab_content_wrapper">
            <div class="container">
                <div class="tab-content" role="tablist">
                    <div id="pane-A" class="card tab-pane fade active show" role="tabpanel" aria-labelledby="tab-A">
                        <div class="card-header" role="tab" id="heading-A">
                            <h5 class="mb-0">
                                <a class="collapsed" data-bs-toggle="collapse" href="#collapse-A" aria-expanded="false"
                                    aria-controls="collapse-A">
                                    Description
                                </a>
                            </h5>
                        </div>
                        <div id="collapse-A" class="collapse" role="tabpanel" aria-labelledby="heading-A">
                            <div class="card-body">
                                <div class="row justify-content-between">
                                    <div class="col-lg-6">
                                        <span id="product_describe">Sed ex labitur adolescens scriptorem. Te saepe verear tibique
                                            sed. Et wisi ridens vix, lorem iudico blandit mel cu. Ex vel sint zril oportere, amet wisi
                                            aperiri te cum.</span>
                                            <hr>
                                        <h3>Details</h3>
                                        <p id="product_detail"></p>
                                        
                                    </div>
                                    <div class="col-lg-5">
                                        <h3>Specifications</h3>
                                        {{-- <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Color</strong></td>
                                                        <td>Blue, Purple</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Size</strong></td>
                                                        <td>150x100x100</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Weight</strong></td>
                                                        <td>0.6kg</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Manifacturer</strong></td>
                                                        <td>Manifacturer</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> --}}
                                        <!-- /table-responsive -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /TAB A -->
                    <div id="pane-B" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
                        <div class="card-header" role="tab" id="heading-B">
                            <h5 class="mb-0">
                                <a class="collapsed" data-bs-toggle="collapse" href="#collapse-B" aria-expanded="false"
                                    aria-controls="collapse-B">
                                    Reviews
                                </a>
                            </h5>
                        </div>
                        <div id="collapse-B" class="collapse" role="tabpanel" aria-labelledby="heading-B">
                            <div class="card-body">
                                <div class="row justify-content-between">
                                    <div class="col-lg-4">
                                        <div class="review_content">
                                            <div class="clearfix add_bottom_10">
                                                <div class="rating_overview" id="rating_overview">
                                                    <center><b id="product_average_rating_value">0/5</b></center>
                                                    <center id="product_average_rating2">
                                                        <i class="icon-star"></i>
                                                        <i class="icon-star"></i>
                                                        <i class="icon-star"></i>
                                                        <i class="icon-star"></i>
                                                        <i class="icon-star"></i>
                                                    </center>
                                                    <center><span class="product_total_rating">4 reviews</span></center>
                                                </div>
                                                <hr>
                                                <h4>RATING BREAKDOWN</h4>
                                                <div class="rating_breakdown">

                                                    <div class="row">
                                                        <div class="col-3">
                                                            <b>5 STARTS</b>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="progress" role="progressbar"
                                                                aria-label="Basic example" aria-valuenow="25"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                <div class="progress-bar" style="width: 25%">25</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <b>4 STARTS</b>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="progress" role="progressbar"
                                                                aria-label="Basic example" aria-valuenow="25"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                <div class="progress-bar" style="width: 50%">50</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <b>3 STARTS</b>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="progress" role="progressbar"
                                                                aria-label="Basic example" aria-valuenow="25"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                <div class="progress-bar" style="width: 69%">69</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <b>2 STARTS</b>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="progress" role="progressbar"
                                                                aria-label="Basic example" aria-valuenow="25"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                <div class="progress-bar" style="width: 31%">31</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <b>1 STARTS</b>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="progress" role="progressbar"
                                                                aria-label="Basic example" aria-valuenow="25"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                <div class="progress-bar" style="width: 20%">20</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8" id="product_feedback">

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="review_image">
                                                    <div class="review_image_load"
                                                        style="background-image: url({{ asset('img/products/shoes/1.jpg') }});"class="item-box">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="review_content">
                                                    <h4><b>Fits:</b>lksdjfhsjkdfhsjfh - dfj</h4>
                                                    <div class="clearfix add_bottom_10">
                                                        <span class="rating"><i class="icon-star"></i><i
                                                                class="icon-star"></i><i class="icon-star"></i><i
                                                                class="icon-star"></i><i
                                                                class="icon-star empty"></i><em>5.0/5.0</em></span>
                                                        <em>Published 54 minutes ago</em>
                                                    </div>
                                                    <h4>"Commpletely satisfied"</h4>
                                                    <p>Eos tollit ancillae ea, lorem consulatu qui ne, eu eros eirmod
                                                        scaevola sea.
                                                        Et nec tantas accusamus salutatus, sit commodo veritus te, erat
                                                        legere
                                                        fabulas has ut. Rebum laudem cum ea, ius essent fuisset ut. Viderer
                                                        petentium cu his.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- /row -->
                                <p class="text-end"><a href="leave-review.html" class="btn_1">Leave a review</a></p>
                            </div>
                            <!-- /card-body -->
                        </div>
                    </div>
                    <!-- /tab B -->
                </div>
                <!-- /tab-content -->
            </div>
            <!-- /container -->
        </div>
        <!-- /tab_content_wrapper -->

        <div class="container margin_60_35">
            <div class="main_title">
                <h2>Related</h2>
                <span>Products</span>
                <div class="owl-carousel owl-theme products_carousel" id="product_related_box">

                </div>
            </div>
            <div class="owl-carousel owl-theme products_carousel">
                <div class="item">
                    <div class="grid_item">
                        <span class="ribbon new">New</span>
                        <figure>
                            <a href="product-detail-1.html">
                                <img class="owl-lazy"
                                    src="{{ asset('img/products/product_placeholder_square_medium.jpg') }}"
                                    data-src="img/products/shoes/4.jpg" alt="">
                            </a>
                        </figure>
                        <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i
                                class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i>
                        </div>
                        <a href="product-detail-1.html">
                            <h3>ACG React Terra</h3>
                        </a>
                        <div class="price_box">
                            <span class="new_price">$110.00</span>
                        </div>
                        <ul>
                            <li><a class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a>
                            </li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to
                                        compare</span></a></li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                        </ul>
                    </div>
                    <!-- /grid_item -->
                </div>
                <!-- /item -->
                <div class="item">
                    <div class="grid_item">
                        <span class="ribbon new">New</span>
                        <figure>
                            <a href="product-detail-1.html">
                                <img class="owl-lazy"
                                    src="{{ asset('img/products/product_placeholder_square_medium.jpg') }}"
                                    data-src="img/products/shoes/5.jpg" alt="">
                            </a>
                        </figure>
                        <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i
                                class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i>
                        </div>
                        <a href="product-detail-1.html">
                            <h3>Air Zoom Alpha</h3>
                        </a>
                        <div class="price_box">
                            <span class="new_price">$140.00</span>
                        </div>
                        <ul>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a>
                            </li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to
                                        compare</span></a></li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                        </ul>
                    </div>
                    <!-- /grid_item -->
                </div>
                <!-- /item -->
                <div class="item">
                    <div class="grid_item">
                        <span class="ribbon hot">Hot</span>
                        <figure>
                            <a href="product-detail-1.html">
                                <img class="owl-lazy"
                                    src="{{ asset('img/products/product_placeholder_square_medium.jpg') }}"
                                    data-src="img/products/shoes/8.jpg" alt="">
                            </a>
                        </figure>
                        <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i
                                class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i>
                        </div>
                        <a href="product-detail-1.html">
                            <h3>Air Color 720</h3>
                        </a>
                        <div class="price_box">
                            <span class="new_price">$120.00</span>
                        </div>
                        <ul>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a>
                            </li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to
                                        compare</span></a></li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                        </ul>
                    </div>
                    <!-- /grid_item -->
                </div>
                <!-- /item -->
                <div class="item">
                    <div class="grid_item">
                        <span class="ribbon off">-30%</span>
                        <figure>
                            <a href="product-detail-1.html">
                                <img class="owl-lazy"
                                    src="{{ asset('img/products/product_placeholder_square_medium.jpg') }}"
                                    data-src="img/products/shoes/2.jpg" alt="">
                            </a>
                        </figure>
                        <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i
                                class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i>
                        </div>
                        <a href="product-detail-1.html">
                            <h3>Okwahn II</h3>
                        </a>
                        <div class="price_box">
                            <span class="new_price">$90.00</span>
                            <span class="old_price">$170.00</span>
                        </div>
                        <ul>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a>
                            </li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to
                                        compare</span></a></li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                        </ul>
                    </div>
                    <!-- /grid_item -->
                </div>
                <!-- /item -->
                <div class="item">
                    <div class="grid_item">
                        <span class="ribbon off">-50%</span>
                        <figure>
                            <a href="product-detail-1.html">
                                <img class="owl-lazy"
                                    src="{{ asset('img/products/product_placeholder_square_medium.jpg') }}"
                                    data-src="img/products/shoes/3.jpg" alt="">
                            </a>
                        </figure>
                        <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i
                                class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i>
                        </div>
                        <a href="product-detail-1.html">
                            <h3>Air Wildwood ACG</h3>
                        </a>
                        <div class="price_box">
                            <span class="new_price">$75.00</span>
                            <span class="old_price">$155.00</span>
                        </div>
                        <ul>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a>
                            </li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to
                                        compare</span></a></li>
                            <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                        </ul>
                    </div>
                    <!-- /grid_item -->
                </div>
                <!-- /item -->
            </div>
            <!-- /products_carousel -->
        </div>
        <!-- /container -->

        <div class="feat">
            <div class="container">
                <ul>
                    <li>
                        <div class="box">
                            <i class="ti-gift"></i>
                            <div class="justify-content-center">
                                <h3>Free Shipping</h3>
                                <p>For all oders over $99</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="box">
                            <i class="ti-wallet"></i>
                            <div class="justify-content-center">
                                <h3>Secure Payment</h3>
                                <p>100% secure payment</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="box">
                            <i class="ti-headphone-alt"></i>
                            <div class="justify-content-center">
                                <h3>24/7 Support</h3>
                                <p>Online top support</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!--/feat-->

    </main>
@endsection
@section('js')
    <!-- SPECIFIC SCRIPTS -->
    <script src="{{ asset('library/js/carousel_with_thumbs.js') }}"></script>

    <script src="{{ asset('modules/product/js/product-detail.js') }}"></script>
@endsection
