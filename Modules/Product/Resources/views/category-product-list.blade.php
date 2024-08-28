@php
    // phpinfo();
@endphp
@extends('product::layouts.master')
@section('css')
    <!-- SPECIFIC CSS -->
    <link href="{{ asset('modules/product/css/listing.css') }}" rel="stylesheet">
@endsection
@section('content')
    <main>
        <div class="top_banner">
            <div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.3)">
                <div class="container">
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Category</a></li>
                            <li>Page active</li>
                        </ul>
                    </div>
                    <h1>Shoes - Grid listing</h1>
                </div>
            </div>
            <img src="img/bg_cat_shoes.jpg" class="img-fluid" alt="">
        </div>
        <!-- /top_banner -->
        <div id="stick_here"></div>
        <div class="toolbox elemento_stick">
            <div class="container">
                <ul class="clearfix">
                    <li>
                        <div class="sort_select">
                            <select name="sort" class="btn-filter" id="sort">
                                <option value="hot" selected="selected">Sort by hot</option>
                                <option value="rating">Sort by average rating</option>
                                <option value="p-asc">Sort by price: low to high</option>
                                <option value="p-desc">Sort by price: high to low </option>
                                <option value="az">A - Z</option>
                                <option value="za">Z - A</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <a href="#0"><i class="ti-view-grid"></i></a>
                        <a href="listing-row-1-sidebar-left.html"><i class="ti-view-list"></i></a>
                    </li>
                    <li>
                        <a href="#0" class="open_filters">
                            <i class="ti-filter"></i><span>Filters</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /toolbox -->
        <div class="container margin_30">
            <div class="row">

                <aside class="col-lg-3" id="sidebar_fixed">
                    <form id="form-filter">
                        <div class="filter_col">
                            <div class="row">
                                <div class="col-6">
                                    <label class="container_check btn-filter" for="sale">Sale
                                        <input type="checkbox" id="sale" name="sale" value ="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="container_check btn-filter" for="new">New
                                        <input type="checkbox" name="new" id="new" value ="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="filter_type version_2">

                                <h4 ><a href="#filter_priceRange" id="labelPriceRange" data-bs-toggle="collapse"
                                        class="closed">Price
                                        Range</a></h4>

                                <input type="checkbox" id="priceRange" class="btn-filter" name="priceRange" value ="1" hidden>


                                <div class="collapse" id="filter_priceRange">
                                    <ul>
                                        <li>
                                            <label for="minPrice">Min Price</label>
                                            <input type="number" class="form-control" placeholder="Min Price" id="minPrice" name="minPrice" disabled>
                                            <div class="box-validation">
                                                 <span class="validation" id="validate_minPrice"> </span>
 
                                            </div>
                                        </li>
                                        <li>
                                            <label for="maxPrice">Max Price</label>
                                            <input type="number" class="form-control" placeholder="Max Price" id="maxPrice" name="maxPrice" disabled>
                                            <div class="box-validation">
                                                <span class="validation" id="validate_maxPrice"> </span>

                                           </div>
                                        </li>
                                    </ul>
                                    <div class="buttons">
                                        <center><a href="#0" class="btn_1" id="btn-filter-price-range">Filter</a></center>
                                    </div>
                                </div>
                            </div>
                            <!-- /filter_type -->


                            <div class="inner_bt"><a href="#" class="open_filters"><i class="ti-close"></i></a></div>
                            <div class="filter_type version_2">
                                <h4><a href="#filter_category" id="filter-category-all" data-bs-toggle="collapse" class="opened">Categories</a></h4>
                                <div class="collapse show box_filter" id="filter_category">
                                </div>
                                <!-- /filter_type -->
                            </div>
                            <!-- /filter_type -->

                            <div class="buttons">
                                {{-- <a href="#0" class="btn_1">Filter</a> <a href="#0" class="btn_1 gray">Reset</a> --}}
                            </div>
                        </div>
                    </form>
                </aside>
                <!-- /col -->
                <div class="col-lg-9">
                    <div id="scroll-top"></div>
                    <div class="row small-gutters" id="box-product-list">
                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <div class="ribbon-group">
                                    <span class="ribbon off">-30%</span>
                                    <span class="ribbon new">New</span>
                                    <span class="ribbon hot">Hot</span>
                                </div>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/1.jpg" alt="">
                                    </a>
                                    <div data-countdown="2019/05/15" class="countdown"></div>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor Air x Fear</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$48.00</span>
                                    <span class="old_price">$60.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <span class="ribbon off">-30%</span>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/2.jpg" alt="">
                                    </a>
                                    <div data-countdown="2019/05/10" class="countdown"></div>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor Okwahn II</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$90.00</span>
                                    <span class="old_price">$170.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <span class="ribbon off">-50%</span>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/3.jpg" alt="">
                                    </a>
                                    <div data-countdown="2019/05/21" class="countdown"></div>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor Air Wildwood ACG</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$75.00</span>
                                    <span class="old_price">$155.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <span class="ribbon new">New</span>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/4.jpg" alt="">
                                    </a>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor ACG React Terra</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$110.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <span class="ribbon new">New</span>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/5.jpg" alt="">
                                    </a>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor Air Zoom Alpha</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$140.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <span class="ribbon new">New</span>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/6.jpg" alt="">
                                    </a>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor Air Alpha</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$130.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <span class="ribbon hot">Hot</span>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/7.jpg" alt="">
                                    </a>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor Air 98</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$115.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <span class="ribbon hot">Hot</span>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/8.jpg" alt="">
                                    </a>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor Air 720</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$120.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <span class="ribbon hot">Hot</span>
                                <figure>
                                    <a href="product-detail-1.html">
                                        <img class="img-fluid lazy"
                                            src="img/products/product_placeholder_square_medium.jpg"
                                            data-src="img/products/shoes/9.jpg" alt="">
                                    </a>
                                </figure>
                                <a href="product-detail-1.html">
                                    <h3>Armor 720</h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">$100.00</span>
                                </div>
                                <ul>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to favorites"><i
                                                class="ti-heart"></i><span>Add to favorites</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to compare"><i
                                                class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                                    <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Add to cart"><i
                                                class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->

                    </div>
                    <!-- /row -->
                    <div class="pagination__wrapper">
                        <ul class="pagination">
                            <li><a href="#0" class="prev" title="previous page">&#10094;</a></li>
                            <li>
                                <a href="#0" class="active">1</a>
                            </li>
                            <li>
                                <a href="#0">2</a>
                            </li>
                            <li>
                                <a href="#0">3</a>
                            </li>
                            <li>
                                <a href="#0">4</a>
                            </li>
                            <li><a href="#0" class="next" title="next page">&#10095;</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /col -->
            </div>
            <!-- /row -->

        </div>
        <!-- /container -->
    </main>
    <!-- /main -->
@endsection
@section('js')
    <!-- SPECIFIC SCRIPTS -->
    <script src="{{ asset('modules/product/js/category-product-list.js') }}"></script>

    <script src="{{ asset('modules/product/js/sticky_sidebar.min.js') }}"></script>

    <script src="{{ asset('modules/product/js/specific_listing.js') }}"></script>
@endsection
