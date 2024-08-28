<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
    @include('user::components.address.delivery-address')
    <div class="form-group">
        <label class="container_check" id="other_addr">Other billing address
            <input type="checkbox" name="otherAddr" value="1">
            <span class="checkmark"></span>
        </label>
    </div>
    @include('user::components.address.billing-address')
</div>
<script src="{{ asset('modules/user/js/address-edit.js') }}"></script>
