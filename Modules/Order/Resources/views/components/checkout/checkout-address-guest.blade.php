<div class="form-group">
    <input type="email" class="form-control" name="deliveryEmail" placeholder="Email">
    <div class="box-validation">
        <span class="validation" id="validate_deliveryEmail"></span>
    </div>
</div>
@include('user::components.address.address')

<script type="text/javascript"
    src="{{ asset('modules/order/js/build-event-select-country-delivery-address-guest.js') }}"></script>
