@php
    // phpinfo();
@endphp
@extends('product::layouts.master')
@section('css')
    <!-- SPECIFIC CSS -->
    <link href="{{ asset('modules/product/css/listing.css') }}" rel="stylesheet">
    <link href="{{ asset('modules/product/css/search.css') }}" rel="stylesheet">
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
                                <option value="score" selected="selected">Sort by matching</option>
                                <option value="hot">Sort by hot</option>
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

                                <h4><a href="#filter_priceRange" id="labelPriceRange" data-bs-toggle="collapse"
                                        class="closed">Price
                                        Range</a></h4>

                                <input type="checkbox" id="priceRange" class="btn-filter" name="priceRange" value ="1"
                                    hidden>


                                <div class="collapse" id="filter_priceRange">
                                    <ul>
                                        <li>
                                            <label for="minPrice">Min Price</label>
                                            <input type="number" class="form-control" placeholder="Min Price"
                                                id="minPrice" name="minPrice" disabled>
                                            <div class="box-validation">
                                                <span class="validation" id="validate_minPrice"> </span>

                                            </div>
                                        </li>
                                        <li>
                                            <label for="maxPrice">Max Price</label>
                                            <input type="number" class="form-control" placeholder="Max Price"
                                                id="maxPrice" name="maxPrice" disabled>
                                            <div class="box-validation">
                                                <span class="validation" id="validate_maxPrice"> </span>

                                            </div>
                                        </li>
                                    </ul>
                                    <div class="buttons">
                                        <center><a href="#0" class="btn_1" id="btn-filter-price-range">Filter</a>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <!-- /filter_type -->


                            <div class="inner_bt"><a href="#" class="open_filters"><i class="ti-close"></i></a></div>
                            <div class="filter_type version_2">
                                <h4><a href="#filter_category" id="filter-category-all" data-bs-toggle="collapse"
                                        class="opened item-category">Categories</a></h4>
                                <div class="collapse show box_filter" id="filter_category">
                                </div>
                                <!-- /filter_type -->
                            </div>
                            <!-- /filter_type -->

                            <div class="filter_type version_2">
                                <h4><a href="#filter_brand" id="filter-brand-all" data-bs-toggle="collapse"
                                        class="opened item-brand">Brand</a></h4>
                                <div class="collapse show" id="filter_brand">
                                    <ul>

                                    </ul>
                                </div>
                            </div>
                            <div class="filter_type version_2">
                                <h4><a href="#filter_sellers" data-bs-toggle="collapse">Sellers</a></h4>
                                <div class="collapse show" id="filter_sellers">
                                    <ul>
                                    </ul>
                                </div>
                            </div>
                            <div class="filter_type version_2">
                                <h4><a href="#filter_specifications" data-bs-toggle="collapse">Specifications</a></h4>
                                <div class="collapse show" id="filter_specifications">
                                    <ul>

                                    </ul>

                                </div>
                            </div>
                            <div class="buttons">
                                {{-- <a href="#0" class="btn_1">Filter</a> <a href="#0" class="btn_1 gray">Reset</a> --}}
                            </div>
                        </div>
                    </form>
                </aside>
                <!-- /col -->
                <div class="col-lg-9">
                    <div id="scroll-top"></div>
                    <div id="search-alternative-keyword">

                    </div>
                    <div class="row small-gutters" id="box-product-list">


                    </div>
                    <center>
                        <ul class="pagination"></ul>
                    </center>
                </div>
                <!-- /col -->
            </div>
            <!-- /row -->

        </div>
        <!-- /container -->
    </main>
    <!-- /main -->
    <!--modal show all seller-->
    <div class="modal fade" id="modal-show-sellers" tabindex="-1" aria-labelledby="modalEditBillingAddress"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"> Seller </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-seller-filter">
                    <div id="box-seller-name-initial">

                    </div>

                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                    <button class="btn btn-warning btn-search" id="btn-sellers-search">Search</button>
                </div>
            </div>
        </div>
    </div>
    <!--/end modal show all seller-->


    <!--modal show all specifications-->
    <div class="modal fade" id="modal-show-specifications" tabindex="-1" aria-labelledby="modalEditBillingAddress"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"> Specifications </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body modal-body-specifications-filter">
                    <div id="box-specifications">

                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                    <button class="btn btn-warning btn-search" id="btn-specifications-search">Search</button>
                </div>
            </div>
        </div>
    </div>
    <!--/end modal show all seller-->
@endsection
@section('js')
    <!-- SPECIFIC SCRIPTS -->
    <script src="{{ asset('modules/product/js/search.js') }}"></script>

    <script src="{{ asset('modules/product/js/sticky_sidebar.min.js') }}"></script>

    <script src="{{ asset('modules/product/js/specific_listing.js') }}"></script>
@endsection
