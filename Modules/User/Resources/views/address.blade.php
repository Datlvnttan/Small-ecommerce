@extends('Layouts.manager.personal')
@section('css')
    <link rel="stylesheet" href="{{ asset('modules/user/css/address.css') }}">
@endsection
@section('personal-content')
    <div class="container-address">
        <div class="header">
            <div class="left">
                <h1>Delivery Address</h1>
            </div>
            <a class="report" id="btn-add-delivery-address" data-bs-toggle="modal"
                data-bs-target="#modal-edit-delivery-address">
                <i class='ti-new'></i>
                <span>New Delivery</span>
            </a>
        </div>
        <hr>
        <div class="box-address" id="box-delivery-address">
            {{-- <div class="item-address box-white">
                <div class="item-address-default">
                    Default
                </div>
                <div class="box-btn-set-default">
                    <strong class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-check2-square" viewBox="0 0 16 16">
                            <path
                                d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5H3z" />
                            <path
                                d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                        </svg>
                        <span>Đặt làm mặc định</span>
                    </strong>
                </div>
                <div class="row">
                    <div class="col-lg-7 col-xxl-8">
                        <span><span class="item-address-recipient-name">Le PHát Dat</span> | <span
                                class="item-address-phone-number">+84387079343</span></span><br>
                        <span class="item-address-type">VietNam</span><br>
                        <span class="item-address-detail">Ấp 1</span><br>
                        <span class="item-address-info">Tây thạnh, Tân Phú, HCM</span><br>
                        <span class="item-address-zip-code">Đây là ghi chú</span>
                    </div>
                    <div class="col-lg-5 col-xxl-4 item-address-btn">
                        <div class="item-address-btn-update">
                            <button class="btn btn-outline-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-trash" viewBox="0 0 16 16">
                                    <path
                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                    <path
                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                                </svg>
                                <span>Delete</span>
                            </button>
                            <button class="btn btn-outline-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                </svg>
                                <span>Update</span>
                            </button>
                        </div>
                    </div>

                </div>
                
            </div> --}}


        </div>
        <div class="header">
            <div class="left">
                <h1>Billing Address</h1>
            </div>
            <a class="report" id="btn-add-billing-address" data-bs-toggle="modal"
                data-bs-target="#modal-edit-billing-address">
                <i class='ti-new'></i>
                <span>New Billing</span>
            </a>
        </div>
        <hr>
        <div class="box-address" id="box-billing-address">



        </div>

    </div>


    <!--modal delivery address-->
    <div class="modal fade" id="modal-edit-delivery-address" tabindex="-1" aria-labelledby="modalEditDeliveryAddress"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditDeliveryAddress"><span id="delivery-operation-title"
                            class="feedback-operation-title">Write</span> a review for "<span
                            id="feedback_product_name">Armor Air X Fear</span>"</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="form-data-delivery-address">

                    <div class="modal-body" style="height:auto; padding: 20px">
                        <div class="">
                            @include('user::components.address.delivery-address')
                            <div class="form-check form-switch">
                                <input class="form-check-input default" type="checkbox" name="deliveryDefault" value="1" role="switch"
                                    id="deliveryDefault">
                                <label class="form-check-label" for="deliveryDefault">Set Default</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-secondary" id="btn-cancel-feedback" data-bs-dismiss="modal">Cancel</a>
                            <button class="btn btn-warning" id="btn-submit-feedback">Submit</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
    <!--/end modal delivery address-->
    @include('user::components.address.modal-edit-delivery-address')
    @include('user::components.address.modal-edit-billing-address')


@endsection
@section('js')
    <script src="{{ asset('modules/user/js/address.js') }}"></script>
    <script src="{{ asset('modules/user/js/address-edit.js') }}"></script>
@endsection
