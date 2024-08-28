<div class="row no-gutters">
    <div class="col-10 form-group pr-1">
        <div class="-select-form form-group">
            <select class="wide add_bottom_15 w-100 form-select" name="deliveryAddressId" id="delivery_address">

            </select>
        </div>
    </div>
    <div class="col-2 form-group pl-1">
        <a href="{{route('web.personal.address')}}" class="btn btn-secondary">Add/Edit</a>
    </div>

    {{-- <div class="col-10 form-group pr-1">
        <div class="-select-form form-group">
            <div class="row">
                <div class="col-lg-7 col-xxl-8">
                    <input type="number" hidden name="deliveryAddressId" id="delivery_address">
                    <span><span class="item-address-recipient-name">Le PHát Dat</span> | <span
                            class="item-address-phone-number">+84387079343</span></span><br>
                    <span class="item-address-detail">Ấp 1</span><br>
                    <span class="item-address-info">Tây thạnh, Tân Phú, HCM</span> - <span
                        class="item-address-country">VietNam</span><br>
                    <span class="item-address-zip-code">70000</span>
                </div>
                <div class="col-lg-5 col-xxl-4 item-address-btn">

                </div>

            </div>
        </div>
    </div>
    <div class="col-2 form-group pl-1">
        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-choose-delivery-address">Choose</a>
    </div> --}}


    <div class="col-lg-12">
        <div class="form-group">
            <select class="add_bottom_15 form-select" name="deliveryInternationalCallingCode"
                id="international_calling_code">
            </select>
            <span class="validation" id="validate_deliveryInternationalCallingCode"></span>
        </div>
        <input type="text w-100" id="phone_number" name="deliveryPhoneNumber" class="form-control"
            placeholder="Telephone">
        <span class="validation" id="validate_deliveryPhoneNumber"></span>
    </div>
</div>
<!--delivery address-->

{{-- @section('end')
    <div class="modal fade" id="modal-choose-delivery-address" tabindex="-1" aria-labelledby="modalChooseDeliveryAddress"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalChooseDeliveryAddress">Delivery Address</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="min-height:500px; padding: 20px">
                    <div id="box-delivery-address">

                    </div>

                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" id="btn-cancel-feedback" data-bs-dismiss="modal">Cancel</a>
                    <button class="btn btn-warning" id="btn-submit-feedback">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    @include('user::components.address.modal-edit-delivery-address')

    <script src="{{ asset('modules/user/js/address.js') }}"></script>
@endsection --}}

<!--/end modal delivery address-->
<!--delivery address-->
