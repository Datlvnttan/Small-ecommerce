$(() => {
    const checkoutKey = HELPER.getParamPrefix();
    const routeApiShippingCountryShippingMethodGetDeliveryCostsByCountry =
        "api.shipping.country.shipping-method.getDeliveryCostsByCountry";
    const routeApiOrderCreateDataOrder = "api.order.createDataOrder";
    const formOrderInformation = $("#form-order-information");
    const btnConfirm = $("#btn_confirm");
    window.CHECKOUT = {
        LOAD: {
            fetchDataCheckout: (key) => {
                new CallApi(route("api.order.checkout", { key: key })).get(
                    (res) => {
                        console.log(res);
                        CHECKOUT.shippingMethodId = res.data.shippingMethodId;
                        CHECKOUT.UI.LOAD_DATA.populateDataCheckout(
                            res.data.orderDetails
                        );
                    },
                    (res) => {
                        console.log(res);
                        $("main").html(
                            `<center><h1>${res.message}</h1></center>`
                        );
                    }
                );
            },
            fetchDataShippingMethods: (shippingMethodId) => {
                new CallApi(route("api.shipping.shipping-method.index")).all(
                    (res) => {
                        CHECKOUT.UI.LOAD_DATA.populateDataShippingMethod(
                            res.data,
                            shippingMethodId
                        );
                    },
                    (res) => {
                        // $('main').html(`<h1>${res.message}</h1>`)
                    }
                );
            },
            callApiGetCouponCode: (couponCode) => {
                // alert(12)
                new CallApi(
                    route("api.discount-coupon.getByCouponCode", {
                        couponCode: couponCode,
                    })
                ).get(
                    (res) => {
                        console.log(res);
                        CHECKOUT.UI.spanErrorDisCoupon.text(
                            `You get an additional ${res.data.discount_percent}% discount`
                        );
                        CHECKOUT.UI.inputCouponCode.data(
                            "discount",
                            res.data.discount
                        );
                        CHECKOUT.UI.LOAD_DATA.loadTotalMoney();
                    },
                    (res) => {
                        CHECKOUT.UI.spanErrorDisCoupon.text(
                            `${res.message}, Coupon does not exist or has expired`
                        );
                        CHECKOUT.UI.inputCouponCode.data("discount", null);
                        CHECKOUT.UI.LOAD_DATA.loadTotalMoney();
                    }
                );
            },
            /**
             * Load chi phí giao hàng theo quốc gia
             * @param {*} countryId
             */
            fetchDataDeliveryCostsByCountry: (countryId) => {
                new CallApi(
                    route(
                        routeApiShippingCountryShippingMethodGetDeliveryCostsByCountry,
                        { countryId: countryId }
                    )
                ).all((res) => {
                    CHECKOUT.UI.LOAD_DATA.populateDataShippingMethod(
                        res.data.shipping_methods,
                        CHECKOUT.shippingMethodId
                    );
                });
            },
            callApiSubmitDataOrder: (dataOrder) => {
                new CallApi(
                    route(routeApiOrderCreateDataOrder, {
                        key: checkoutKey,
                    })
                ).post(
                    dataOrder,
                    (res) => {
                        // console.log(res);
                        location.replace(res.data);
                    },
                    (res,status) => {
                        // console.log(res);
                        if(status!=409)
                            handleCreateToast("error", res.message ?? res.error, null, true);
                        else
                        {
                            Swal.fire({
                                title: "Checkout failed!!!",
                                text: res.error,
                                icon: "error",
                                showCancelButton: false,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Ok",
                            }).then((result) => {
                               location.replace(route('web.cart.index'))
                            });
                        }
                        
                        // if (res.errors) {
                        //     return HELPER.showErrorValidates(res.errors);
                        // }
                    }
                );
            },
        },
        UI: {
            total: $("#total"),
            btnAddCouponCode: $("#btn_add_coupon_code"),
            btnRemoveCouponCode: $("#btn_remove_coupon_code"),
            inputCouponCode: $("#coupon_code"),
            spanErrorDisCoupon: $("#error-discount-coupon"),
            ulOrderDetail: $("#order-detail"),
            liSale: $("#sale"),
            spanShippingFee: $("#shipping-fee"),
            divShippingItems: $("#shipping-items"),

            LOAD_DATA: {
                populateDataCheckout: (orderDetails) => {
                    // orderDetails = data.orderDetails;
                    if (orderDetails == null || orderDetails.length == 0) {
                        $("main").html("<center><h2>Not found</h2></center>");
                    }
                    CHECKOUT.UI.ulOrderDetail.empty();
                    let totalMoney = 0;
                    orderDetails.forEach((orderDetail) => {
                        const itemOrderDetail =
                            CHECKOUT.UI.LOAD_DATA.buildItemOrderDetail(
                                orderDetail
                            );
                        CHECKOUT.UI.ulOrderDetail.append(itemOrderDetail);
                        totalMoney =
                            parseFloat(totalMoney) +
                            parseFloat(orderDetail.subtotal);
                    });
                    CHECKOUT.UI.ulOrderDetail.data(
                        "items-total-money",
                        totalMoney
                    );
                    $("#subtotal").text(
                        `$${totalMoney.toLocaleString("en-us")}`
                    );
                    // CHECKOUT.LOAD.fetchDataShippingMethods(
                    //     data.shippingMethodId
                    // );
                    CHECKOUT.UI.EVENT.buildEventButtonAddCouponCode();
                    CHECKOUT.UI.EVENT.buildEventButtonRemoveCouponCode();
                },
                /**
                 * Tạo UI cho 1 item order detail
                 * @param {*} orderDetail
                 * @returns itemOrderDetail,subtota
                 */
                buildItemOrderDetail: (orderDetail) => {
                    // let subtotal = (
                    //     orderDetail.cart_quantity * orderDetail.price_new
                    // ).toFixed(2);
                    let html = `<li class="clearfix product-item-order">
									<div class="product-img" style="background-image: url(${orderDetail.image_path}); background-size:cover"></div>
									<em><strong>${orderDetail.product_name}</strong><br>${
                        orderDetail.options ?? ""
                    }<br>${orderDetail.cart_quantity} x $${
                        orderDetail.price_new_format
                    }</em><span>$${orderDetail.subtotal_format}</span>
								</li>`;
                    const itemOrderDetail = $(html);
                    itemOrderDetail.data("subtotal", orderDetail.subtotal);
                    return itemOrderDetail;
                },
                /**
                 *
                 * @param {*} shippingMethods
                 * @param {*} shippingMethodId
                 */
                populateDataShippingMethod(shippingMethods, shippingMethodId) {
                    // const divShippingItems = $("#shipping-items");
                    if (shippingMethods.length == 0) {
                        CHECKOUT.UI.divShippingItems.html(
                            "<li><h4>No shipping methods available</h4></li>"
                        );
                        return;
                    }
                    CHECKOUT.UI.divShippingItems.empty();
                    shippingMethods.forEach((shippingMethod) => {
                        const shippingMethodItem =
                            CHECKOUT.UI.LOAD_DATA.buildItemShippingMethod(
                                shippingMethod,
                                shippingMethodId
                            );
                        CHECKOUT.UI.divShippingItems.append(shippingMethodItem);
                    });
                },
                buildItemShippingMethod: (shippingMethod, shippingMethodId) => {
                    let html = `<div class="col-4"  style="padding-bottom: 5px">
                                    <div class="justify-content-center">
                                        <div class="border border-dark shipping-item  p-2">
                                        <input type="radio" value="${shippingMethod.shipping_method_id}" class="shipping-method-checkbox" name="shippingMethodId">
                                            <center><b>${shippingMethod.shipping_method_name}</b></center>`;
                    if (
                        shippingMethod.shipping_method_country_discount_percent >
                        0
                    )
                        html += `<center><b class="shipping-expense-new">${shippingMethod.shipping_method_country_expense_new_format} USD save ${shippingMethod.shipping_method_country_discount_percent}%</b></center>
                                <center><p class="shipping-expense-old">${shippingMethod.shipping_method_country_expense_old_format} USD</p></center>`;
                    else
                        html += `<center><b class="shipping-expense-new">${shippingMethod.shipping_method_country_expense_new_format} USD</b></center>`;
                    html += `               <center>
                                                <b class="shipping-expense-delivery-time">About ${shippingMethod.shipping_method_country_delivery_time} days</b>
                                            </center>
                                        </div>
                                    </div>
                                </div>`;
                    const elementShippingMethod = $(html);
                    const checkBox =
                        elementShippingMethod.find('[type="radio"]');
                    const shippingItem =
                        elementShippingMethod.find(".shipping-item");
                    checkBox.data("id", shippingMethod.shipping_method_id);
                    checkBox.data(
                        "obj-shipping-method",
                        JSON.stringify(shippingMethod)
                    );
                    // console.log(JSON.stringify(shippingMethod));
                    CHECKOUT.UI.EVENT.buildEventSelectedShippingMethod(
                        elementShippingMethod,
                        shippingItem,
                        checkBox
                    );
                    CHECKOUT.UI.EVENT.buildEventSelectedCheckboxShippingMethod(
                        checkBox
                    );
                    if (
                        (shippingMethodId == null &&
                            shippingMethod.default == true) ||
                        shippingMethodId == shippingMethod.shipping_method_id
                    ) {
                        CHECKOUT.UI.EFFECT.elementShippingMethodSelected(
                            shippingItem,
                            checkBox
                        );
                    }
                    return elementShippingMethod;
                },
                loadTotalMoney: (checkboxShippingMethodItem = null) => {
                    let discountCoupon =
                        CHECKOUT.UI.inputCouponCode.data("discount");
                    let total = parseFloat(
                        CHECKOUT.UI.ulOrderDetail.data("items-total-money")
                    );
                    if (checkboxShippingMethodItem == null) {
                        checkboxShippingMethodItem = $(
                            'input[type="radio"][name="shippingMethodId"]:checked'
                        );
                    }
                    // console.log(checkboxShippingMethodItem)
                    // console.log(shippingMethodItem.data("obj-shipping-method"))
                    let shippingFee = 0;
                    let sale = {};
                    if (discountCoupon) {
                        sale.coupon = (discountCoupon * total).toLocaleString(
                            "en-GB"
                        );
                        total -= discountCoupon * total;
                    }
                    if (
                        checkboxShippingMethodItem.data("obj-shipping-method")
                    ) {
                        const objShippingMethod = JSON.parse(
                            checkboxShippingMethodItem.data(
                                "obj-shipping-method"
                            )
                        );
                        if (objShippingMethod == undefined) {
                            return;
                        }
                        shippingFee =
                            objShippingMethod.shipping_method_country_expense_old_format;
                        if (
                            objShippingMethod.shipping_method_country_discount_percent >
                            0
                        ) {
                            sale.shipping =
                                objShippingMethod.discount_amount_format;
                        }
                        total +=
                            objShippingMethod.shipping_method_country_expense_new;
                    }
                    let htmlSale = "";
                    for (const key in sale) {
                        if (Object.hasOwnProperty.call(sale, key)) {
                            htmlSale += `<span>-$${sale[key]}(${key})</span>`;
                        }
                    }
                    if (htmlSale.length > 0) {
                        htmlSale = "<em><strong>Sale</strong></em>" + htmlSale;
                    } else {
                        htmlSale =
                            "<em><strong>Sale</strong></em><span>- $0</span>";
                    }
                    CHECKOUT.UI.liSale.html(htmlSale);
                    CHECKOUT.UI.spanShippingFee.html(`$${shippingFee}`);
                    CHECKOUT.UI.total.text(`$${total.toLocaleString("en-GB")}`);
                },
                setUpForm(form) {
                    var formArray = $(form).serializeArray();
                    var formData = {};

                    $.each(formArray, function (i, field) {
                        if (field.value !== "") {
                            formData[field.name] = field.value;
                        }
                    });

                    return formData;
                },
            },
            EVENT: {
                /**
                 * thiết lập sự kiện khi chọn vào phương thức vận chuyển
                 * @param {*} elementShippingMethod
                 * @param {*} shippingItem
                 * @param {*} checkBox
                 */
                buildEventSelectedShippingMethod: (
                    elementShippingMethod,
                    shippingItem,
                    checkBox
                ) => {
                    elementShippingMethod.click(function () {
                        CHECKOUT.UI.EFFECT.elementShippingMethodSelected(
                            shippingItem,
                            checkBox
                        );
                    });
                },
                buildEventSelectedCheckboxShippingMethod: (checkBox) => {
                    checkBox.change(function () {
                        const id = $(this).data("id");
                        CHECKOUT.selectId = id;
                        // CHECKOUT.LOAD.callApiGetShippingMethod(id)
                    });
                },
                buildEventButtonAddCouponCode: () => {
                    CHECKOUT.UI.btnAddCouponCode.click(() => {
                        let couponCode = CHECKOUT.UI.inputCouponCode.val();
                        if (couponCode == null || couponCode == "") {
                            return handleCreateToast(
                                "error",
                                "Please enter coupon code",
                                "error-please-enter-coupon-code",
                                true
                            );
                        }
                        CHECKOUT.LOAD.callApiGetCouponCode(couponCode);
                    });
                },
                buildEventButtonRemoveCouponCode: () => {
                    CHECKOUT.UI.btnRemoveCouponCode.click(() => {
                        CHECKOUT.UI.inputCouponCode.val("");
                        CHECKOUT.UI.spanErrorDisCoupon.text("-");
                        CHECKOUT.UI.inputCouponCode.data("discount", null);
                        CHECKOUT.UI.LOAD_DATA.loadTotalMoney();
                    });
                },
                buildEventConfirmOrder: () => {
                    btnConfirm.click(() => {
                        const dataOrder =
                            CHECKOUT.UI.LOAD_DATA.setUpForm(
                                formOrderInformation
                            ); //.serialize();
                        // const dataOrder = formOrderInformation.serialize();
                        // console.log(dataOrder);
                        HELPER.hideErrorValidates();
                        CHECKOUT.LOAD.callApiSubmitDataOrder(dataOrder);
                    });
                },
            },
            EFFECT: {
                elementShippingMethodSelected: (shippingItem, checkBox) => {
                    $(".shipping-item.selected").removeClass("selected");
                    shippingItem.addClass("selected");
                    checkBox.prop("checked", true);
                    CHECKOUT.shippingMethodId = checkBox.data("id");
                    checkBox.change();
                    // console.log(checkBox.data('obj-shipping-method'))
                    CHECKOUT.UI.LOAD_DATA.loadTotalMoney(checkBox);
                },
            },
        },
    };
    CHECKOUT.UI.divShippingItems.html(
        "<li><h6>Please select your shipping address to determine shipping methods</h6></li>"
    );
    CHECKOUT.LOAD.fetchDataCheckout(checkoutKey);
    buildEventSelectCountryDeliveryAddress(
        (countryId, internationalCallingCode) => {
            // alert(ADDRESS.UI.selectInternationalCallingCode.data("change"))
            if (
                ADDRESS.UI.selectInternationalCallingCode.data("change") ==
                false
            ) {
                ADDRESS.UI.selectInternationalCallingCode
                    .find(`option[value="${internationalCallingCode}"]`)
                    .prop("selected", true);
            }
            CHECKOUT.LOAD.fetchDataDeliveryCostsByCountry(countryId);
        }
    );
    CHECKOUT.UI.EVENT.buildEventConfirmOrder();
});
