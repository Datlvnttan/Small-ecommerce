{{-- @php
    dd($user)
@endphp --}}
@if ($user->isNotDeliveryAddress())
    @include('order::components.checkout.checkout-not-delivery-address-member')
    <script type="text/javascript"
        src="{{ asset('modules/order/js/build-event-select-country-delivery-address-guest.js') }}"></script>
@else
    @include('order::components.checkout.checkout-delivery-address-member')
    <script type="text/javascript"
        src="{{ asset('modules/order/js/build-event-select-country-delivery-address-member.js') }}"></script>
@endif
<!--delivery address-->
@if ($user->isNotBillingAddress())
    <div class="form-group">
        <label class="container_check" id="other_addr">Other billing address
            <input type="checkbox" name="otherAddr" value="1">
            <span class="checkmark"></span>
        </label>
    </div>
    @include('order::components.checkout.checkout-not-billing-address-member')
@else
    @include('order::components.checkout.checkout-billing-address-member')
@endif
<!--billing address-->

{{-- @include('order::components.checkout.checkout-delivery-address-member')
<!--delivery address-->
@include('order::components.checkout.checkout-billing-address-member') --}}
<!--billing address-->
<script src="{{ asset('modules/user/js/address-edit.js') }}"></script>
<script type="text/javascript" src="{{ asset('modules/order/js/checkout-address-member.js') }}"></script>
