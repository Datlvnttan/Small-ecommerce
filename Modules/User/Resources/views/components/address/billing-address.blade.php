{{-- <div class="form-group">
    <label class="container_check" id="other_addr">Other billing address
        <input type="checkbox" name="otherAddr" value="1">
        <span class="checkmark"></span>
    </label>
</div> --}}
<div id="other_addr_c" class="pt-2">
    <div class="form-group">
        <input type="text" class="form-control fullname" name="billingFullname" placeholder="Full name">
        <div class="box-validation">
            <span class="validation" id="validate_billingFullname"></span>
        </div>
    </div>
    <div class="form-group">
        <select class="form-select w-100 country_id" name="countryBillingAddressId" id="country_billing_address">
            <option value="" selected>Country</option>
            {{-- <option value="Europe">Europe</option>
            <option value="United states">United states</option>
            <option value="Asia">Asia</option> --}}
        </select>
        <div class="box-validation">
            <span class="validation" id="validate_countryBillingAddressId"></span>

        </div>
    </div>
    <!-- /row -->
    <div class="form-group">
        <input type="text" class="form-control address_specific" name="billingAddressSpecific" placeholder="Address specific">
        <div class="box-validation">
            <span class="validation" id="validate_billingAddressSpecific"></span>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-lg-4 form-group pr-1">
            <input type="text" class="form-control province" name="billingProvince" placeholder="Province">
            <div class="box-validation">
                <span class="validation" id="validate_billingProvince"></span>
            </div>
        </div>
        <div class="col-lg-4 form-group pl-1">
            <input type="text" class="form-control district" name="billingDistrict" placeholder="District">
            <div class="box-validation">
                <span class="validation" id="validate_billingDistrict"></span>
            </div>
        </div>
        <div class="col-lg-4 form-group pr-1">
            <input type="text" class="form-control ward" name="billingWard" placeholder="Ward">
            <div class="box-validation">
                <span class="validation" id="validate_billingWard"></span>
            </div>
        </div>
        <div class="form-group pl-1">
            <input type="text" class="form-control zip_code" name="billingZipCode" placeholder="Zip code">
            <div class="box-validation">
                <span class="validation" id="validate_billingZipCode"></span>
            </div>
        </div>
    </div>
    <!-- /row -->

    <div class="form-group">
        <input type="text" class="form-control tax_id_number" name="billingTaxIDNumber" placeholder="Tax ID number">
        <div class="box-validation">
            <span class="validation" id="validate_billingTaxIDNumber"></span>
        </div>
    </div>
    @yield('input')
</div>
<!-- /other_addr_c -->
<hr>
