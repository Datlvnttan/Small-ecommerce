<div class="form-group">
    <input type="text" class="form-control fullname" name="deliveryFullname" placeholder="Full name">
    <div class="box-validation">
        <span class="validation" id="validate_deliveryFullname"></span>
    </div>
</div>
<div class="form-group">
    <select class="w-100 form-select country_id" name="countryDeliveryAddressId"
        id="country_delivery_address">
    </select>
    <div class="box-validation">
        <span class="validation" id="validate_countryDeliveryAddressId"></span>
    </div>
</div>
<!-- /row -->
<div class="form-group">
    <input type="text" class="form-control address_specific" name="deliveryAddressSpecific" placeholder="Address specific">
    <div class="box-validation">
        <span class="validation" id="validate_deliveryAddressSpecific"></span>
    </div>
</div>
<div class="row no-gutters">
    <div class="col-lg-4 form-group pr-1">
        <input type="text" class="form-control province" name="deliveryProvince" placeholder="Province">
        <div class="box-validation">
            <span class="validation" id="validate_deliveryProvince"></span>
        </div>
    </div>
    <div class="col-lg-4 form-group pl-1">
        <input type="text" class="form-control district" name="deliveryDistrict" placeholder="District">
        <div class="box-validation">
            <span class="validation" id="validate_deliveryDistrict"></span>
        </div>
    </div>
    <div class="col-lg-4 form-group pr-1">
        <input type="text" class="form-control ward" name="deliveryWard" placeholder="Ward">
        <div class="box-validation">
            <span class="validation" id="validate_deliveryWard"></span>
        </div>
    </div>
    <div class="form-group pl-1">
        <input type="text" class="form-control zip_code" name="deliveryZipCode" placeholder="Zip code">
        <div class="box-validation">
            <span class="validation" id="validate_deliveryZipCode"></span>
        </div>
    </div>
</div>
<!-- /row -->

<div class="form-group">
    <div class="row">
        <div class="">
            <select class="add_bottom_15 international_calling_code" name="deliveryInternationalCallingCode" id="international_calling_code">
            </select>
            <div class="box-validation">
                <span class="validation" id="validate_deliveryInternationalCallingCode"></span>
            </div>
        </div>
        <div class="">
            <input type="text" class="form-control phone_number" name="deliveryPhoneNumber" placeholder="Mobie phone">
            <div class="box-validation">
                <span class="validation" id="validate_deliveryPhoneNumber"></span>
            </div>
        </div>
    </div>
    @yield('input')
</div>
<!-- /form -->
<hr>
