{{-- <div class="form-group">
    <input type="email" class="form-control" name="deliveryEmail" placeholder="Email">
    <span class="validation" id="validate_deliveryEmail"></span>
</div> --}}
@extends('user::components.address.delivery-address')
@section('input')
<div class="form-group">
    <label class="container_check" for="save_delivery_address">Save this delivery address
        <input type="checkbox" id="save_delivery_address" name="saveDeliveryAddress" value="1">
        <span class="checkmark"></span>
    </label>
</div>
@endsection