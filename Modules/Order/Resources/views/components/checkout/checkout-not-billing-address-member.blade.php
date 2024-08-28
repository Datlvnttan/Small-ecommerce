@extends('user::components.address.billing-address')
@section('input')
<div class="form-group">
    <label class="container_check" for="save_billing_address">Save this billing address
        <input type="checkbox" id="save_billing_address" name="saveBillingAddress" value="1">
        <span class="checkmark"></span>
    </label>
</div>
@endsection