
    <!--modal billing address-->
    <div class="modal fade" id="modal-edit-billing-address" tabindex="-1" aria-labelledby="modalEditBillingAddress"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditBillingAddress">Edit Billing Address</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="form-data-billing-address">
                    <div class="modal-body" style="height:auto; padding: 20px">
                        <div class="">
                            @include('user::components.address.billing-address')
                            <div class="form-check form-switch">
                                <input class="form-check-input default" type="checkbox" name="billingDefault" value="1" role="switch"
                                    id="billingDefault">
                                <label class="form-check-label" for="billingDefault">Set Default</label>
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
    <!--/end modal billing address-->