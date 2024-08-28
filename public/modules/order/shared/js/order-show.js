$(()=>{
    const orderId = HELPER.getParamPrefix();
const feedbackProductName = $("#feedback_product_name");
const feedbackOption = $("#feedback-option");
const formFeedback = $("#form-feedback");
const feedbackChooseImage = $("#feedback-choose-image");
const feedbackTitle = $("#feedback_title");
const feedbackReview = $("#feedback_review");
const modalFeedback = $("#modal-feedback");
const feedbackShowImage = $("#feedback-show-image");
const feedbackIncognito = $("#feedback_incognito");
const btnSubmitFeedback = $("#btn-submit-feedback");
const btnDeleteFeedback = $("#btn-delete-feedback");
const feedbackOperationTitle = $("#feedback-operation-title");
modalFeedback.data("enable", true);
const routeFeedbackStore = "api.order.feedback.store";
const routeFeedbackUpdate = "api.order.feedback.update";
const routeFeedbackDelete = "api.order.feedback.destroy";
// alert(route(routeFeedbackStore))
window.ORDER_DETAIL = {
    LOAD: {
        fetchDataOrderDetail: (id) => {
            new CallApi(route("api.order.show", { id: id })).get(
                (res) => {
                    console.log(res);
                    ORDER_DETAIL.UI.LOAD_DATA.populateDataOrderDetail(res.data);
                },
                (res) => {
                    console.log(res);
                    $(".container").html(
                        `<center><h2>${res.error ?? "404"}</h2></center>`
                    );
                }
            );
        },
        submitFeedback: (routeName, skuId, data) => {
            new CallApi(
                route(routeName, {
                    orderId: orderId,
                    skuId: skuId,
                })
            ).post(
                data,
                (res) => {
                    console.log(res);
                    location.reload();
                },
                (res) => {
                    console.log(res);
                    handleCreateToast("error", res.message ?? res.error, null, true);
                },
                "",
                false,
                false
            );
        },
        removeFeedback: (skuId) => {
            new CallApi(
                route(routeFeedbackDelete, {
                    orderId: orderId,
                    skuId: skuId,
                })
            ).delete(
                null,
                (res) => {
                    return window.location.reload(true);
                },
                (res) => {
                    console.log(res);
                    handleCreateToast("error", res.message ?? res.error, null, true);
                }
            );
        },
    },
    UI: {
        LOAD_DATA: {
            populateDataOrderDetail: (order) => {
                // console.log(order);
                $("#order-order_key").text('['+order.id+']' + order.order_key ?? '');
                $("#order-status").text(order.current_status);

                $("#is_paid").text(order.is_paid ? "PAID" : "UNPAID");
                $("#order-delivery_address_fullname").text(
                    order.delivery_address.fullname
                );
                $("#order-delivery_address_phone_number").text(
                    HELPER.formatPhoneNumber(
                        order.delivery_address.phone_number,
                        order.delivery_address.international_calling_code
                    )
                );
                let address = `${order.delivery_address.ward ?? "_"}, ${
                    order.delivery_address.district ?? "_"
                }, ${order.delivery_address.province ?? "_"}, ${
                    order.delivery_address.country ?? "_"
                }`;
                $("#order-delivery_address_address").text(address);
                $("#order-delivery_address_address_specific").text(
                    order.delivery_address.address_specific ?? ""
                );
                $("#order-delivery_address_zip_code").text(
                    order.delivery_address.zip_code
                );
                if (order.billing_address) {
                    $("#order-billing_address_fullname").text(
                        order.billing_address.fullname
                    );
                    address = `${order.billing_address.ward ?? "_"}, ${
                        order.billing_address.district
                    }, ${order.billing_address.province}, ${
                        order.billing_address.country
                    }`;
                    $("#order-billing_address_address").text(address);
                    $("#order-billing_address_address_specific").text(
                        order.billing_address.address_specific ?? ""
                    );
                    $("#order-billing_address_zip_code").text(
                        order.billing_address.zip_code
                    );
                    $("#order-billing_address_tax_id_number").text(
                        order.billing_address.tax_id_number
                    );
                }

                $("#order-email").text(order.email);
                $("#order-shipping_method_shipping_method_name").text(
                    order.shipping_method.shipping_method_name
                );
                $("#order-payment_method").text(order.payment_method);

                $("#order-note").text(order.note ?? "_");
                $("#order-created_at").text(
                    HELPER.convertDateTimeToString(order.created_at)
                );
                $("#order-detail-quantity").text(
                    `[${order.order_details.length} products]`
                );
                $("#order-total_point").text(order.total_point);
                $("#order-shipping_method_expense").text(
                    order.shipping_method.expense
                );
                $("#order-shipping_method_discount").text(
                    (
                        order.shipping_method.expense *
                         order.shipping_method.discount
                    ).toLocaleString("en-US")
                );
                $("#order-total_amount").text(
                    order.total_amount.toLocaleString("en-US")
                );
                const subtotal =
                    ORDER_DETAIL.UI.LOAD_DATA.buildItemOrderDetails(
                        order.order_details,
                        order.is_evaluate
                    );
                $("#order-subtotal").text(subtotal.toLocaleString("en-US"));
                let dis = 0;
                if (order.discount_coupon) {
                    dis = order.discount_coupon.discount * 100;
                    $("#order-discount_coupon").text(
                        `${order.discount_coupon.coupon_code}(-${dis}%)`
                    );
                } else {
                    $("#order-discount_coupon").remove();
                    dis = 0;
                }
                $("#order-discount_coupon_discount").text(
                    (subtotal *(order.discount_coupon.discount ?? 0)).toLocaleString(
                        "en-US"
                    )
                );
                if (order.is_allowed_cancel)
                    ORDER_DETAIL.UI.EVENT.buildButtonCancelOrder();
            },
            buildItemOrderDetails: (orderDetails, createFeedback) => {
                const boxItemOrderDetail = $(".box-order-details-products");
                boxItemOrderDetail.empty();
                var subtotal = 0;
                var totalQuantity = 0;
                orderDetails.forEach((orderDetail) => {
                    totalQuantity += orderDetail.quantity;
                    subtotal += orderDetail.price * orderDetail.quantity;
                    const itemOrderDetail =
                        ORDER_DETAIL.UI.LOAD_DATA.buildItemOrderDetail(
                            orderDetail,
                            createFeedback
                        );
                    boxItemOrderDetail.append(itemOrderDetail);
                });
                $("#order-details-total-quantity").text(totalQuantity);
                return subtotal;
            },
            buildItemOrderDetail: (orderDetail, createFeedback) => {
                const itemOrderDetail = $(`<div class="item-order-product">
                                <div class="row">
                                    <div class="col-lg-2 col-xxl-1 col-md-3 col-sm-4 col-5">
                                        <div class="image-product" style="background: url(${orderDetail.image_path}); background-size: cover; ">                                                
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-xxl-11 col-md-9 col-sm-8 col-7">
                                        <div class="box-btn-create-feedback"></div>  
                                        <strong class="item-product-name text-dark">${
                                            orderDetail.product_name
                                        }</strong><br>
                                        
                                        <span class="item-product-classify">${
                                            orderDetail.options ?? "_"
                                        }</span><br>
                                        <span class="item-product-quantity">$${
                                            orderDetail.price
                                        } x ${orderDetail.quantity}</span>
                                        <strong class="item-product-price">$${(
                                            orderDetail.quantity *
                                            orderDetail.price
                                        ).toLocaleString("en-US")}</strong>
                                    </div>
                                </div>
                            </div>`);
                if (createFeedback == true) {
                    var btnFeedback = null;
                    if (orderDetail.feedback_created_at == null) {
                        btnFeedback =
                            ORDER_DETAIL.UI.EVENT.buildButtonFeedback(
                                orderDetail
                            );
                    } else {
                        btnFeedback =
                            ORDER_DETAIL.UI.EVENT.buildButtonShowFeedback(
                                orderDetail
                            );
                    }
                    itemOrderDetail
                        .find(".box-btn-create-feedback")
                        .append(btnFeedback);
                }
                return itemOrderDetail;
            },

            loadFeedback: (orderDetail) => {
                $(`#${orderDetail.feedback_rating}_star`).prop("checked", true);
                feedbackTitle.val(orderDetail.feedback_title);
                feedbackReview.text(orderDetail.feedback_review);
                if (orderDetail.feedback_incognito) {
                    feedbackIncognito.prop("checked", true);
                }
                ORDER_DETAIL.UI.LOAD_DATA.enableFormFeedback(
                    !orderDetail.feedback_status
                );
                let operationTitle = null;
                let btnSubmitFeedbackText = null;
                let modalFeedbackData = null;
                let isBuildBtnRemove = null;
                if (orderDetail.feedback_status) {
                    operationTitle = "See";
                    btnSubmitFeedbackText = "Cannot press";
                    isBuildBtnRemove = false;
                    btnDeleteFeedback.hide();
                    // feedbackOperationTitle.text('See')
                    // btnSubmitFeedback.text("Cannot press")
                    // modalFeedback.data('add',null)
                } else {
                    operationTitle = "Update";
                    btnSubmitFeedbackText = "Update";
                    modalFeedbackData = false;
                    isBuildBtnRemove = true;
                    btnDeleteFeedback.show();
                    // modalFeedback.data('add',false)
                    // btnSubmitFeedback.text('Update')
                    // feedbackOperationTitle.text('Update')
                }
                modalFeedback.data("add", modalFeedbackData);
                btnSubmitFeedback.text(btnSubmitFeedbackText);
                feedbackOperationTitle.text(operationTitle);
                if (orderDetail.feedback_image != null) {
                    ORDER_DETAIL.UI.LOAD_DATA.showFeedbackImage(
                        orderDetail.feedback_path_image,
                        isBuildBtnRemove,
                        orderDetail.feedback_image
                    );
                } else {
                    feedbackShowImage.empty();
                }
            },
            showFeedbackImage: (
                fileUrl,
                buildBtnRemove = true,
                feedbackImage = null
            ) => {
                if (fileUrl == null) {
                    feedbackShowImage.empty();
                    return;
                }
                // alert(feedbackImage ?? 'null')
                const divImage = $(`<div class="show-image w-100 h-100" >
                                        <img src="${fileUrl}" class="w-100 h-100" />
                                        ${
                                            feedbackImage
                                                ? `<input name="feedbackImageOld" value="${feedbackImage}" hidden>`
                                                : ""
                                        }
                                    </div>`);
                if (buildBtnRemove) {
                    const btnRemoveImage =
                        $(`<button class="btn item-remove-image">
                                                    <i class="ti-trash">xóa</i>                                                                 
                                                </button>`);
                    divImage.append(btnRemoveImage);
                    btnRemoveImage.click(function () {
                        divImage.remove();
                        if (feedbackChooseImage.length > 0)
                            feedbackChooseImage.val("");
                    });
                }
                feedbackShowImage.empty();
                feedbackShowImage.append(divImage);
                return divImage;
            },
            resetFormFeedback: () => {
                feedbackTitle.val("");
                feedbackReview.val("");
                feedbackIncognito.prop("checked", false);
                feedbackChooseImage.val("");
                feedbackShowImage.empty();
                const feedbackRating = $(
                    'input[name="feedbackRating"]:checked'
                );
                if (feedbackRating.length > 0) {
                    feedbackRating.prop("checked", false);
                }
                ORDER_DETAIL.UI.LOAD_DATA.enableFormFeedback(true);
                btnSubmitFeedback.text("Confirm");
                modalFeedback.data("add", true);
                feedbackOperationTitle.text("Write");
                console.log(formFeedback)
                btnDeleteFeedback.hide();
            },
            enableFormFeedback: (value) => {
                modalFeedback.data("enable", value);
                formFeedback.prop("disabled", !value);
                feedbackTitle.prop("disabled", !value);
                feedbackReview.prop("disabled", !value);
                feedbackIncognito.prop("disabled", !value);
                feedbackChooseImage.prop("disabled", !value);

                formFeedback.prop("readonly", !value);
                feedbackTitle.prop("readonly", !value);
                feedbackReview.prop("readonly", !value);
                feedbackIncognito.prop("readonly", !value);
                feedbackChooseImage.prop("readonly", !value);

                const feedbackRatings = $('input[name="feedbackRating"]');
                feedbackRatings.each(function () {
                    $(this).prop("disabled", !value);
                });
            },
        },

        EVENT: {
            buildButtonFeedback: (orderDetail) => {
                // createModalFeedback();
                const btnFeedback = $(
                    `<a class="btn-create-feedback" id="btn-feedback-${orderDetail.sku_id}" data-bs-toggle="modal" data-bs-target="#modal-feedback">Feedback</a>`
                );
                // var btnConfirmFeedback = $("#btn-confirm-feedback");
                btnFeedback.click(function () {
                    // modalFeedback.modal('show');
                    feedbackProductName.text(
                        orderDetail.sku.product.product_name
                    );
                    feedbackOption.text(orderDetail.options);
                    formFeedback.data("sku-id", orderDetail.sku_id);
                    HELPER.hideErrorValidates();
                    ORDER_DETAIL.UI.LOAD_DATA.resetFormFeedback();
                    btnDeleteFeedback.hide();
                });
                return btnFeedback;
            },
            buildButtonShowFeedback: (orderDetail) => {
                const btnShowFeedback = $(
                    `<a class="btn-show-feedback" id="btn-show-feedback-${orderDetail.sku_id}" data-bs-toggle="modal" data-bs-target="#modal-feedback">Show Feedback</a>`
                );
                // var btnConfirmFeedback = $("#btn-confirm-feedback");
                btnShowFeedback.click(function () {
                    feedbackProductName.text(
                        orderDetail.sku.product.product_name
                    );
                    feedbackOption.text(orderDetail.options);
                    formFeedback.data("sku-id", orderDetail.sku_id);
                    HELPER.hideErrorValidates();
                    ORDER_DETAIL.UI.LOAD_DATA.loadFeedback(orderDetail);
                });
                return btnShowFeedback;
            },
            buildButtonFeedbackChooseImage: () => {
                HELPER.buildInputChooseImage(
                    feedbackChooseImage,
                    ORDER_DETAIL.UI.LOAD_DATA.showFeedbackImage,
                    true,
                    feedbackShowImage,
                    true
                );
            },
            buildEventFormFeedback: () => {
                // event submit feedback
                formFeedback.on("submit", function (e) {
                    e.preventDefault();
                    const checkAdd = modalFeedback.data("add");
                    // alert(checkAdd)
                    if (checkAdd == undefined || checkAdd == null) {
                        handleCreateToast(
                            "error",
                            "Feedback has been approved, you are not allowed to update",
                            "warning-feedback",
                            true
                        );
                        return;
                    }
                    const data = $(this).serializeArray();
                    const formData = new FormData();
                    for (let i = 0; i < data.length; i++) {
                        formData.append(data[i].name, data[i].value);
                    }
                    if (
                        feedbackChooseImage.val() != "" &&
                        feedbackChooseImage[0].files[0] != null
                    ) {
                        formData.append(
                            "feedbackImage",
                            feedbackChooseImage[0].files[0]
                        );
                    }

                    // console.log(formData);
                    const skuId = formFeedback.data("sku-id");
                    // console.log(feedbackChooseImage[0].files)
                    if (checkAdd) {
                        return ORDER_DETAIL.LOAD.submitFeedback(
                            routeFeedbackStore,
                            skuId,
                            formData
                        );
                    } else {
                        return ORDER_DETAIL.LOAD.submitFeedback(
                            routeFeedbackUpdate,
                            skuId,
                            formData
                        );
                    }
                });
            },
            buildBtnRemoveFeedback: () => {
                btnDeleteFeedback.click(() => {
                    const checkAdd = modalFeedback.data("add");
                    if (checkAdd == null) {
                        return handleCreateToast(
                            "error",
                            "Feedback has been approved, you are not allowed to update",
                            "warning-feedback",
                            true
                        );
                    }
                    if (checkAdd == true) {
                        return handleCreateToast(
                            "error",
                            "You have not created feedback for this item",
                            "error-delete-feedback",
                            true
                        );
                    }
                    const skuId = formFeedback.data("sku-id");
                    if (skuId == null) {
                        handleCreateToast(
                            "error",
                            "Feedback has been approved, you are not allowed to delete",
                            "error-feedback-delete-1",
                            true
                        );
                        return;
                    }
                    HELPER.messageDelete((result) => {
                        if (result.isConfirmed) {
                            ORDER_DETAIL.LOAD.removeFeedback(skuId);
                        }
                    });
                });
            },
            buildButtonCancelOrder: () => {
                const boxOrderDetailsFooter = $(".box-order-details-footer");
                boxOrderDetailsFooter.append(
                    '<center><button class="btn btn-danger btn-cancel-order">Cancel Order</button></center>'
                );
                const btnCancelOrder =
                    boxOrderDetailsFooter.find(".btn-cancel-order");
                btnCancelOrder.click(() => {
                    ORDER_DETAIL.UI.EVENT.buildDialogCancelOrder();
                });
            },
            buildDialogCancelOrder: () => {
                Swal.fire({
                    title: "You want to cancel your order!!!",
                    text: "Please enter a reason for canceling your order:",
                    icon: "warning",
                    input: "text",
                    inputAttributes: {
                        autocapitalize: "off",
                    },
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                    showLoaderOnConfirm: true,
                    preConfirm: async (reason) => {
                        try {
                            if (!reason.trim()) {
                                Swal.showValidationMessage(
                                    "Please enter a reason for canceling your order."
                                );
                                return false;
                            }
                            const urlCancelOrder = route(
                                "api.order.cancelOrder",
                                {
                                    orderId: orderId,
                                    reason: reason,
                                }
                            );
                            return new Promise((resolve, reject) => {
                                new CallApi(urlCancelOrder).patch(
                                    null,
                                    null,
                                    (res) => {
                                        resolve(res);
                                    },
                                    (res) => {
                                        reject(res);
                                    }
                                );
                            });
                        } catch (error) {
                            Swal.showValidationMessage(`
                          Request failed: ${error}
                        `);
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    console.log(result);
                    res = result.value;
                    if (result.isConfirmed) {
                        console.log(res);
                        if (res.data != null) {
                            return location.replace(res.data.url);
                        }
                        return window.location.reload(true);
                    } else {
                        handleCreateToast(
                            "error",
                            res.message,
                            "error-cancel-order",
                            true
                        );
                    }

                    // if (result.isConfirmed) {
                    //     handleCreateToast("success", res.message, null, true);
                    //     if (!res.data.isMember &&
                    //         typeof buildEventDialogConfirmOTP ===
                    //             "function") {
                    //                 buildEventDialogConfirmOTP('api.order.cancelOrderGuestEnterOTP','Confirm order cancellation',res.message,"PATCH",{
                    //             orderId: orderId,
                    //         },(resultOTP)=>{
                    //             if (resultOTP.isConfirmed) {
                    //                 handleCreateToast("success", resultOTP.value.message, null, true);
                    //             } else {
                    //                 handleCreateToast("error",resultOTP.value.message,"error-cancel-order",true);
                    //             }
                    //         });
                    //     }
                    // } else {
                    //     handleCreateToast("error",res.message,"error-cancel-order",true);
                    // }
                });
            },
        },
        EFFECT: {},
    },
};
ORDER_DETAIL.LOAD.fetchDataOrderDetail(orderId);
ORDER_DETAIL.UI.EVENT.buildEventFormFeedback();
ORDER_DETAIL.UI.EVENT.buildButtonFeedbackChooseImage();
ORDER_DETAIL.UI.EVENT.buildBtnRemoveFeedback();
})