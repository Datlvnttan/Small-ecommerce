<link href="{{ asset('modules/order/manager/personal/css/leave_review.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('modules/order/manager/personal/css/order-show.css') }}">
<div class="container">
    <div class="box-white">
        <div class="box-order-details">
            <div class="box-order-details-header">
                <div class="box-order-details-header-left">
                    <a href="{{route(Auth::check() ? 'web.order.personal.order.index' : 'web.home.index')}}">
                        < <span>Back</span>
                    </a>
                </div>
                <div class="box-order-details-header-right">
                    <span class="item-order-details-code-status"><span>ID: </span><span class="item-order-details-code"
                            id="order-order_key">230820611YYAPF</span> - [<span class="item-order-details-status"
                            id="order-status">Cancled</span>] - <strong class="status-payment"
                            id="is_paid">UNPAID</strong></span>
                </div>
                <div style="clear: both"></div>
            </div>
            <div class="box-order-details-content">
                <div class="box-order-details-content-buyer-info row">
                    <div class="col-lg-4 box-order-details-content-buyer-info-address">
                        <center class="address-title">Delivery Address</center>
                        <div>
                            <strong class="info-address-name-phone text-dark"><span class="info-address-name"
                                    id="order-delivery_address_fullname">Lê Phát Đạt</span> | <span
                                    class="info-address-phone-number"
                                    id="order-delivery_address_phone_number">0387079343</span></strong><br>
                            <span>Address: <span class="info-address-info" id="order-delivery_address_address">Tây
                                    thạnh, Tân Phú, Hồ Chí Minh</span></span><br>
                            <span>Address Specific: <span class="info-address-detail"
                                    id="order-delivery_address_address_specific">Số nhà 63</span></span><br>
                            <span>Zip code: <span class="info-address-info"
                                    id="order-delivery_address_zip_code">112211544</span></span><br>
                            {{-- <span>Shipping method: <span class="info-order-details-shipping-method">Cart éh</span></span>                                
                        <div class="">Payment: <span class="payment-method">Thanh toán khi nhận hàng</span></div>                                                         --}}
                        </div>
                    </div>
                    <div class="col-lg-4 box-order-details-content-buyer-info-address">
                        <center class="address-title">Billing Address</center>
                        <div>
                            <strong class="info-address-name-phone text-dark"><span class="info-address-name"
                                    id="order-billing_address_fullname">Lê Phát Đạt</span></strong><br>
                            <span>Address: <span class="info-address-info" id="order-billing_address_address">Tây thạnh,
                                    Tân Phú, Hồ Chí Minh</span></span><br>
                            <span>Address Specific: <span class="info-address-detail"
                                    id="order-billing_address_address_specific">Số nhà 63</span></span><br>
                            <span>Zip code: <span class="info-address-info"
                                    id="order-billing_address_zip_code">112211544</span></span><br>
                            <span>Tax ID Number: <span class="info-order-details-shipping-method"
                                    id="order-billing_address_tax_id_number">123-23898322-83923</span></span>
                            {{-- <div class="">Payment: <span class="payment-method">Thanh toán khi nhận hàng</span></div>                                                         --}}
                        </div>
                    </div>
                    <div class="col-lg-4 box-order-details-content-buyer-info-address">
                        <center class="address-title">Orther</center>
                        <div>
                            <span>Email: <span class="info-address-info"
                                    id="order-email">email@gmail.com</span></span><br>
                            <span>Shipping Method: <span class="info-address-detail"
                                    id="order-shipping_method_shipping_method_name">Loại ship</span></span><br>
                            <span>Payment: <span class="info-address-detail"
                                    id="order-payment_method">Payment</span></span><br>
                            <span>Total Points: <span class="info-address-info"
                                    id="order-total_point">233</span></span><br>
                            <span>Discount coupon: <span class="info-order-details-shipping-method"
                                    id="order-discount_coupon">hd73g7ef(-23%)</span></span><br>
                            <span class="info-address-note">Note:<span id="order-note">note</span></span><br>
                            {{-- <div class="">Payment: <span class="payment-method">Thanh toán khi nhận hàng</span></div>  --}}
                            {{-- <center><strong class="info-address-name-phone text-dark"><span class="info-address-name">UNPAID</span></strong></center>                                                      --}}
                        </div>
                    </div>
                </div>
                <div class="text-dark">Order at: <span class="created_at"
                        id="order-created_at">2024-07-18T07:18:05</span></div>
                <div class="box-order-details-content-main">
                    <div class="box-order-info-shop">
                        <span class="item-order-shop-name text-dark">Order Details</span>
                        <button class="btn-order-shop-chat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-chat-right-quote" viewBox="0 0 16 16">
                                <path
                                    d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1H2zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12z" />
                                <path
                                    d="M7.066 4.76A1.665 1.665 0 0 0 4 5.668a1.667 1.667 0 0 0 2.561 1.406c-.131.389-.375.804-.777 1.22a.417.417 0 1 0 .6.58c1.486-1.54 1.293-3.214.682-4.112zm4 0A1.665 1.665 0 0 0 8 5.668a1.667 1.667 0 0 0 2.561 1.406c-.131.389-.375.804-.777 1.22a.417.417 0 1 0 .6.58c1.486-1.54 1.293-3.214.682-4.112z" />
                            </svg>
                            Chat
                        </button>
                        {{-- <a class="btn-order-shop-access">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop-window" viewBox="0 0 16 16">
                            <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zm2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        Xem shop
                    </a> --}}
                        <a class="btn-feedback-shop" id="order-detail-quantity">[3 products]</a>
                        <div style="clear: both"></div>
                    </div>
                    <div class="box-order-details-products">

                        <div class="item-order-product">
                            <div class="row">
                                <div class="col-lg-2 col-xxl-1 col-md-3 col-sm-4 col-5">
                                    <div class="image-product"
                                        style="background: url(${URL_HOST}uploads/${don_hang.cua_hang_id}/${san_pham_id}/${chi_tiet.san_pham.anh_bia}); background-size: cover; ">
                                    </div>
                                </div>
                                <div class="col-lg-10 col-xxl-11 col-md-9 col-sm-8 col-7">
                                    <strong class="item-product-name text-dark">Tên sản phẩm</strong><br>
                                    <span class="item-product-classify">Options</span><br>
                                    <span class="item-product-quantity">x 9</span>
                                    <strong class="item-product-price">19.000.000</strong>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="box-order-tatol-summary">
                        <div class="row text-dark">
                            <div class="col-8 text-right ">Total Quantity:</div>
                            <div class="col-4 text-right" id="order-details-total-quantity">30</div>
                            <div class="col-8 text-right ">Subtotal:</div>
                            <div class="col-4 text-right">$<span id="order-subtotal"></span></div>
                            <div class="col-8 text-right ">Shipping Expense</div>
                            <div class="col-4 text-right ">+ $<span id="order-shipping_method_expense">2232</span></div>
                            <div class="col-8 text-right ">Shipping Sale:</div>
                            <div class="col-4 text-right ">- $<span id="order-shipping_method_discount"></span></div>
                            <div class="col-8 text-right ">Other Sale</div>
                            <div class="col-4 text-right ">- $<span id="order-discount_coupon_discount">23</span>
                            </div>
                            <div class="col-8 title-tatol-summary">Total amount:</div>
                            <div class="col-4 tatol-summary into-money">$<span
                                    id="order-total_amount">90.000.000đ</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-order-details-footer">
                
            </div>
        </div>
    </div>
</div>

@section('end')
        <!--Feedback-->
        <div class="modal fade" id="modal-feedback" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><span id="feedback-operation-title" class="feedback-operation-title">Write</span> a review for "<span
                                id="feedback_product_name">Armor Air X Fear</span>"</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" enctype="multipart/form-data" id="form-feedback">
                        <div class="modal-body" style="height:auto; padding: 20px">
                            <div class="row justify-content-center">
                                <div class="write_review">
                                    <h6>option:<b id="feedback-option"></b></h6>
                                    <div class="rating_submit">
                                        <div class="form-group">
                                            <label class="d-block">Overall rating</label>
                                            <span class="rating mb-0">
                                                <input type="radio" class="rating-input" id="5_star"
                                                    name="feedbackRating" value="5" value="5 Stars"><label
                                                    for="5_star" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="4_star"
                                                    name="feedbackRating" value="4" value="4 Stars"><label
                                                    for="4_star" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="3_star"
                                                    name="feedbackRating" value="3" value="3 Stars"><label
                                                    for="3_star" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="2_star"
                                                    name="feedbackRating" value="2" value="2 Stars"><label
                                                    for="2_star" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="1_star"
                                                    name="feedbackRating" value="1" value="1 Star"><label
                                                    for="1_star" class="rating-star"></label>
                                            </span><br>
                                            <div class="box-validation">
                                                <span class="validation" id="validate_feedbackRating"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /rating_submit -->
                                    <div class="form-group">
                                        <label>Title of your review</label>
                                        <input class="form-control" type="text" name="feedbackTitle" id="feedback_title"
                                            placeholder="If you could say it in one sentence, what would you say?">
                                        <div class="box-validation">
                                            <span class="validation" id="validate_feedbackTitle"></span>
                                        </div>
    
                                        <div class="form-group">
                                            <label>Your review</label>
                                            <textarea class="form-control" style="height: 180px;" name="feedbackReview" id="feedback_review"
                                                placeholder="Write your review to help others learn about this online business"></textarea>
                                                <div class="box-validation">
                                                    <span class="validation" id="validate_feedbackReview"></span>
                                                </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label>Add your photo (optional)</label>
                                                <div class="fileupload"><input type="file" id="feedback-choose-image" name="feedbackImage"
                                                        accept="image/*">
                                                </div>
                                                <div class="box-validation">
                                                    <span class="validation" id="validate_feedbackImage"></span>
                                                </div>
                                                {{-- <span class="validation" id="validate_feedbackImage">123</span> --}}
                                            </div>
                                            
                                        </div>
                                        <div class="feedback-show-image" id="feedback-show-image">
                                            
                                        </div>
                                        
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="feedbackIncognito" id="feedback_incognito">
                                        <label class="form-check-label" for="feedback_incognito">Incognito</label>
                                      </div>
                                    <p>The review content will be approved before posting, you can still edit until then</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a class="btn btn-outline-danger" id="btn-delete-feedback">delete</a>
                                <a class="btn btn-secondary" id="btn-cancel-feedback" data-bs-dismiss="modal">Cancel</a>
                                <button class="btn btn-warning" id="btn-submit-feedback">Confirm Feedback</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

