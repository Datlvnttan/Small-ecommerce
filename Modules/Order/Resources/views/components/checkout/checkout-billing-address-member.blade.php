<div class="form-group">
    <label class="container_check" for="other_addr">Other billing address
        <input type="checkbox" checked name="otherAddr" value="1" id="other_addr">
        <span class="checkmark"></span>
    </label>
</div>
<div class="row no-gutters" id="box-select-billing-address">
    <h6>Billing address</h6>
    <div class="col-10 form-group pr-1">
        <div class="form-group">
            <select class="wide form-select add_bottom_15 w-100" name="billingAddressId" id="billing_address">

            </select>
        </div>
    </div>
    <div class="col-2 form-group pl-1">
        <a href="{{route('web.personal.address')}}" class="btn btn-secondary">Add/Edit</a>
    </div>
</div>
<!--billing address-->