@extends('main::layouts.master')

@section('content')
    <main>
        <div class="header-video">
            <div id="hero_video">
                <div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                    <div class="container">
                        <div class="row justify-content-center justify-content-md-start">
                            <div class="col-lg-6">
                                <div class="slide-text white">
                                    <h3>Armor Air<br>Max 720 Sage Low</h3>
                                    <p>Limited items available at this price</p>
                                    <a class="btn_1" href="#0" role="button">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <img src="img/video_fix.png" alt="" class="header-video--media" data-video-src="video/intro"
                data-teaser-source="video/intro" data-provider="" data-video-width="1920" data-video-height="960">
        </div>
        <!-- /header-video -->

        {{-- <ul id="banners_grid" class="clearfix">
        <li>
            <a href="#0" class="img_container">
                <img src="img/banners_cat_placeholder.jpg" data-src="img/banner_1.jpg" alt="" class="lazy">
                <div class="short_info opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                    <h3>Men's Collection</h3>
                    <div><span class="btn_1">Shop Now</span></div>
                </div>
            </a>
        </li>
        <li>
            <a href="#0" class="img_container">
                <img src="img/banners_cat_placeholder.jpg" data-src="img/banner_2.jpg" alt="" class="lazy">
                <div class="short_info opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                    <h3>Womens's Collection</h3>
                    <div><span class="btn_1">Shop Now</span></div>
                </div>
            </a>
        </li>
        <li>
            <a href="#0" class="img_container">
                <img src="img/banners_cat_placeholder.jpg" data-src="img/banner_3.jpg" alt="" class="lazy">
                <div class="short_info opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                    <h3>Kids's Collection</h3>
                    <div><span class="btn_1">Shop Now</span></div>
                </div>
            </a>
        </li>
    </ul>
    <!--/banners_grid --> --}}

        <div class="bg_gray">
            <div class="container margin_30">
                <div id="categories-hot" class="brands owl-carousel owl-theme">
                    {{-- <div class="item-category-hot">
                    <a href="#0">
                        <center>dgshgd sjjhfsdjkf skjh sd lshd </center>
                    </a>
                </div> --}}
                </div><!-- /carousel -->
            </div><!-- /container -->
        </div>
        <!-- /bg_gray -->

        <div class="container margin_60_35">
            <div class="main_title">
                <h2>Flash sale </h2> | <strong id="flash-sale-status">Happening</strong>
                <span>Products</span>
                <a href="#">See all</a>
            </div>
            <div class="owl-carousel owl-theme products_carousel" id="product_flash_sale_box">
                {{-- <div class="item">
                <div class="grid_item">
                    <div class="ribbon-group">
                        <span class="ribbon new">New</span>
                        <span class="ribbon hot">Hot</span>
                    </div>
                    <figure>
                        <a href="product-detail-1.html">
                            <img class="owl-lazy" src="img/products/product_placeholder_square_medium.jpg" data-src="img/products/shoes/4.jpg" alt="">
                        </a>
                    </figure>
                    <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i></div>
                    <a href="product-detail-1.html">
                        <h3>ACG React Terra</h3>
                    </a>
                    <div class="price_box">
                        <span class="new_price">$110.00</span>
                    </div>
                    <ul>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
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
                            <img class="owl-lazy" src="img/products/product_placeholder_square_medium.jpg" data-src="img/products/shoes/5.jpg" alt="">
                        </a>
                    </figure>
                    <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i></div>
                    <a href="product-detail-1.html">
                        <h3>Air Zoom Alpha</h3>
                    </a>
                    <div class="price_box">
                        <span class="new_price">$140.00</span>
                    </div>
                    <ul>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
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
                            <img class="owl-lazy" src="img/products/product_placeholder_square_medium.jpg" data-src="img/products/shoes/8.jpg" alt="">
                        </a>
                    </figure>
                    <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i></div>
                    <a href="product-detail-1.html">
                        <h3>Air Color 720</h3>
                    </a>
                    <div class="price_box">
                        <span class="new_price">$120.00</span>
                    </div>
                    <ul>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
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
                            <img class="owl-lazy" src="img/products/product_placeholder_square_medium.jpg" data-src="img/products/shoes/2.jpg" alt="">
                        </a>
                    </figure>
                    <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i></div>
                    <a href="product-detail-1.html">
                        <h3>Okwahn II</h3>
                    </a>
                    <div class="price_box">
                        <span class="new_price">$90.00</span>
                        <span class="old_price">$170.00</span>
                    </div>
                    <ul>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
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
                            <img class="owl-lazy" src="img/products/product_placeholder_square_medium.jpg" data-src="img/products/shoes/3.jpg" alt="">
                        </a>
                    </figure>
                    <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i></div>
                    <a href="product-detail-1.html">
                        <h3>Air Wildwood ACG</h3>
                    </a>
                    <div class="price_box">
                        <span class="new_price">$75.00</span>
                        <span class="old_price">$155.00</span>
                    </div>
                    <ul>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                        <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                    </ul>
                </div>
                <!-- /grid_item -->
            </div>
            <!-- /item --> --}}
            </div>
            <!-- /products_carousel -->
        </div>
        <!-- /Flash sale -->

        <div class="container margin_60_35">
            <div class="main_title">
                <h2>Product New</h2>
                <span>Products</span>
                <a href="#">See all</a>
            </div>
            <div class="owl-carousel owl-theme products_carousel" id="product_new_box">
            </div>
            <!-- /products_carousel -->
        </div>
        <!-- /Product New-->

        <div class="bg_gray">
            <div class="container margin_30">
                <div id="brands" class="owl-carousel owl-theme">
                    <div class="item">
                        <a href="#0"><img src="img/brands/placeholder_brands.png" data-src="img/brands/logo_1.png"
                                alt="" class="owl-lazy"></a>
                    </div><!-- /item -->
                    <div class="item">
                        <a href="#0"><img src="img/brands/placeholder_brands.png" data-src="img/brands/logo_2.png"
                                alt="" class="owl-lazy"></a>
                    </div><!-- /item -->
                    <div class="item">
                        <a href="#0"><img src="img/brands/placeholder_brands.png" data-src="img/brands/logo_3.png"
                                alt="" class="owl-lazy"></a>
                    </div><!-- /item -->
                    <div class="item">
                        <a href="#0"><img src="img/brands/placeholder_brands.png" data-src="img/brands/logo_4.png"
                                alt="" class="owl-lazy"></a>
                    </div><!-- /item -->
                    <div class="item">
                        <a href="#0"><img src="img/brands/placeholder_brands.png" data-src="img/brands/logo_5.png"
                                alt="" class="owl-lazy"></a>
                    </div><!-- /item -->
                    <div class="item">
                        <a href="#0"><img src="img/brands/placeholder_brands.png" data-src="img/brands/logo_6.png"
                                alt="" class="owl-lazy"></a>
                    </div><!-- /item -->
                </div><!-- /carousel -->
            </div><!-- /container -->
        </div>
        <!-- /bg_gray -->


        <div class="container margin_60_35">
            <div class="main_title">
                <h2>Brand Hot</h2>
                <span>Brands</span>
                <a href="#">See all</a>
            </div>
            <div class="row" id="brand_hot">
                {{-- <div class="col-lg-6">
                    <a class="box_news" href="blog.html">
                        <figure>
                            <img src="img/blog-thumb-placeholder.jpg" data-src="img/blog-thumb-1.jpg" alt=""
                                width="400" height="266" class="lazy">

                        </figure>
                        <h4>Brand name</h4>
                        <div class="row">
                            <div class="col-6">Tổng mua</div>
                            <div class="col-6">Tổng review</div>
                        </div>
                    </a>
                    <div class="row">
                        <div class="owl-carousel owl-theme brand_products_carousel">
                    
                        </div>
                    </div>
                </div>
                <!-- /brand_hot -->
                <div class="col-lg-6">
                    <a class="box_news" href="blog.html">
                        <figure>
                            <img src="img/blog-thumb-placeholder.jpg" data-src="img/blog-thumb-2.jpg" alt=""
                                width="400" height="266" class="lazy">
                            <figcaption><strong>28</strong>Dec</figcaption>
                        </figure>
                        <ul>
                            <li>By Jhon Doe</li>
                            <li>20.11.2017</li>
                        </ul>
                        <h4>Duo eius postea suscipit ad</h4>
                        <p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum
                            vidisse....</p>
                    </a>
                    <div class="row">
                        <div class="owl-carousel owl-theme brand_products_carousel">
                            
                        </div>
                    </div>
                </div> --}}
                <!-- /brand_hot -->
            </div>
            <!-- /row -->
        </div>

        <div class="container margin_60_35">
            <div class="main_title">
                <h2>Latest News</h2>
                <span>Blog</span>
                <a href="#">See all</a>
            </div>
            <div class="row" id="post_new">
                {{-- <div class="col-lg-6">
                    <a class="box_news" href="blog.html">
                        <figure>
                            <img src="img/blog-thumb-placeholder.jpg" data-src="img/blog-thumb-1.jpg" alt=""
                                width="400" height="266" class="lazy">
                            <figcaption><strong>28</strong>Dec</figcaption>
                        </figure>
                        <ul>
                            <li>by Mark Twain</li>
                            <li>20.11.2017</li>
                        </ul>
                        <h4>Pri oportere scribentur eu</h4>
                        <p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum
                            vidisse....</p>
                    </a>
                </div>
                <!-- /box_news -->
                <div class="col-lg-6">
                    <a class="box_news" href="blog.html">
                        <figure>
                            <img src="img/blog-thumb-placeholder.jpg" data-src="img/blog-thumb-2.jpg" alt=""
                                width="400" height="266" class="lazy">
                            <figcaption><strong>28</strong>Dec</figcaption>
                        </figure>
                        <ul>
                            <li>By Jhon Doe</li>
                            <li>20.11.2017</li>
                        </ul>
                        <h4>Duo eius postea suscipit ad</h4>
                        <p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum
                            vidisse....</p>
                    </a>
                </div>
                <!-- /box_news -->
                <div class="col-lg-6">
                    <a class="box_news" href="blog.html">
                        <figure>
                            <img src="img/blog-thumb-placeholder.jpg" data-src="img/blog-thumb-3.jpg" alt=""
                                width="400" height="266" class="lazy">
                            <figcaption><strong>28</strong>Dec</figcaption>
                        </figure>
                        <ul>
                            <li>By Luca Robinson</li>
                            <li>20.11.2017</li>
                        </ul>
                        <h4>Elitr mandamus cu has</h4>
                        <p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum
                            vidisse....</p>
                    </a>
                </div>
                <!-- /box_news -->
                <div class="col-lg-6">
                    <a class="box_news" href="blog.html">
                        <figure>
                            <img src="img/blog-thumb-placeholder.jpg" data-src="img/blog-thumb-4.jpg" alt=""
                                width="400" height="266" class="lazy">
                            <figcaption><strong>28</strong>Dec</figcaption>
                        </figure>
                        <ul>
                            <li>By Paula Rodrigez</li>
                            <li>20.11.2017</li>
                        </ul>
                        <h4>Id est adhuc ignota delenit</h4>
                        <p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum
                            vidisse....</p>
                    </a>
                </div> --}}
                <!-- /box_news -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </main>
    <!-- /main -->
    <script src="{{ asset('modules/main/home.js') }}"></script>
@endsection
