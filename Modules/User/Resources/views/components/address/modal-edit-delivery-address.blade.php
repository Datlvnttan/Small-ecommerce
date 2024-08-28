    <!--modal delivery address-->
    <div class="modal fade" id="modal-edit-delivery-address" tabindex="-1" aria-labelledby="modalEditDeliveryAddress"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditDeliveryAddress">Edit Delivery Address</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="form-data-delivery-address">

                    <div class="modal-body" style="height:auto; padding: 20px">
                        <div class="">
                            @include('user::components.address.delivery-address')
                            <div class="form-check form-switch">
                                <input class="form-check-input default" type="checkbox" name="deliveryDefault" value="1" role="switch"
                                    id="deliveryDefault">
                                <label class="form-check-label" for="deliveryDefault">Set Default</label>
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
    <!--/end modal delivery address-->