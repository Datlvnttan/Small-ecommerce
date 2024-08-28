$(() => {
    window.CART = {
        LOAD: {
            /**
             * Gọi api lấy giỏ hàng
             */
            fetchDataCarts: () => {
                new CallApi(route("api.cart.index")).all(
                    (res) => {
                        console.log(res);
                        CART.data = res.data;
                        CART.UI.LOAD_DATA.populateDataCarts(res.data);
                        HEADER.populateDataCartIntoHeader(res.data);
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("error", res.message, null, true);
                    }
                );
            },
            /**
             * gọi api để xóa sản phẩm khỏi giỏ hàng
             * @param {*} skuId
             */
            callApiRemoveProductFromCart: (skuId) => {
                new CallApi(route("api.cart.destroy", { skuId: skuId })).delete(
                    undefined,
                    (res) => {
                        console.log(res);
                        handleCreateToast("success", res.message, null, true);
                        CART.LOAD.fetchDataCarts();
                        // CART.LOAD.callApiGetShippingMethod(CART.selectId)
                        // CART.UI.LOAD_DATA.refreshTotalMoney();
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("error", res.message, null, true);
                    }
                );
            },
            /**
             * gọi api để cập nhật số lượng sản phẩm trong giỏ hàng
             * @param {*} skuId
             * @param {*} quantity
             */
            callApiUpdateQuantityProductInTheCart: (skuId, quantity) => {
                new CallApi(route("api.cart.update", { skuId: skuId })).update(
                    null,
                    {
                        quantity: quantity,
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("success", res.message, null, true);
                        CART.LOAD.fetchDataCarts();
                        // CART.LOAD.callApiGetShippingMethod(CART.selectId)
                        // CART.UI.LOAD_DATA.refreshTotalMoney();
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("error", res.message, null, true);
                    }
                );
            },
            /**
             * Gọi api để lấy tất cả các phương thức vận chuyển
             */
            fetchDataShippingMethod: () => {
                if (!CART.SHIPPING_METHOD_DATA) {
                    // new CallApi(route("api.shipping.shipping-method.index")).all(
                    new CallApi(
                        route(
                            "api.shipping.country.shipping-method.getDeliveryCostsByCountry"
                        )
                    ).all(
                        (res) => {
                            console.log(res);
                            CART.UI.LOAD_DATA.populateDataShippingMethod(
                                res.data.shipping_methods
                            );
                            CART.SHIPPING_METHOD_DATA =
                                res.data.shipping_methods;
                        },
                        (res) => {
                            console.log(res);
                        }
                    );
                } else {
                    CART.UI.LOAD_DATA.refreshTotalMoney();
                }
            },
            // /**
            //  * gọi api lấy dữ liệu về 1 phương thức vận chuyển
            //  * @param {*} id
            //  */
            // callApiGetShippingMethod: (id) => {
            //     new CallApi(route("api.shipping.shipping-method.show",{id:id})).show(null,
            //         (res) => {
            //             CART.UI.LOAD_DATA.updateTotalMoney(res.data);
            //         },
            //         (res) => {
            //             console.log(res);
            //         }
            //     );
            // },
            callApiBuildDataOrder: (shippingMethodId) => {
                new CallApi(
                    route("api.order.build-data-order", {
                        shippingMethodId: shippingMethodId,
                    })
                ).post(
                    null,
                    (res) => {
                        location.replace(res.data);
                    },
                    (res) => {
                        handleCreateToast("error", res.error, null, true);
                        console.log(res);
                    }
                );
            },
        },
        UI: {
            liSubtotal: $("#subtotal"),
            liShippingFee: $("#shipping-fee"),
            liSubtotal: $("#subtotal"),
            liSale: $("#sale"),
            liTotal: $("#total"),
            btnCheckout: $("#btn_checkout"),

            LOAD_DATA: {
                populateDataCarts: (cartItems) => {
                    if (cartItems == null || cartItems.length == 0)
                        return $("#main-cart").html(
                            "<center><h2>Cart is empty</h2></center>"
                        );
                    const tbodyCartData = $("#cart_data");
                    CART.LOAD.fetchDataShippingMethod();
                    tbodyCartData.empty();
                    var enabledBtnCheckout = true;
                    cartItems.forEach((cartItem) => {
                        const row = CART.UI.LOAD_DATA.builtItemCart(cartItem);
                        tbodyCartData.append(row);
                        if (!cartItem.is_stock_sufficient) {
                            enabledBtnCheckout = false;
                        }
                    });
                    if (!enabledBtnCheckout) {
                        CART.UI.btnCheckout.prop("disabled", true);
                        CART.UI.btnCheckout.addClass("disabled");
                    } else {
                        CART.UI.btnCheckout.prop("disabled", false);
                        CART.UI.btnCheckout.removeClass("disabled");
                    }
                },
                builtItemCart: (cartItem) => {
                    const trCartItem = $(`<tr>
                                <td><a href="${route("web.product.show", {
                                    id: cartItem.product_id,
                                })}">
                                    <div class="thumb_cart">
                                        <img src="${cartItem.image_path}" data-src="img/products/shoes/1.jpg" class="lazy" alt="Image">
                                    </div>
                                    <h5 class="item_cart">${
                                        cartItem.product_name
                                    }</h5>
                                    <span class="item_cart item_cart_option">${
                                        cartItem.options ?? ""
                                    }</span>
                                    </a>
                                </td>
                                <td>
                                    <strong>$${
                                        cartItem.price_new_format
                                    }</strong>
                                </td>
                                <td class="td-edit-quantity">
                                    <div class="numbers-row">
                                        <div class="inc button_inc">+</div><div class="dec button_inc">-</div>
                                    </div>
                                </td>
                                <td>
                                    <strong>$${
                                        cartItem.subtotal_format
                                    }</strong>
                                </td>
                                <td class="options">
                                    <a class="options-click cart_remove"><i class="ti-trash"></i></a>
                                    <a class="options-click disabled cart_update"><i class="ti-save"></i></a>
                                </td>
                            </tr>`);
                    HELPER.buildButtonInc(trCartItem.find(".inc"), 1);
                    HELPER.buildButtonInc(trCartItem.find(".dec"), 1);
                    const buttonCartItemRemove = trCartItem.find(
                        ".options-click.cart_remove"
                    );
                    CART.UI.EVENT.buildEventRemoveProductFromCart(
                        buttonCartItemRemove,
                        cartItem.sku_id
                    );
                    const buttonCartItemUpdate = trCartItem.find(
                        ".options-click.cart_update"
                    );
                    const inputCartQuantity = $(
                        `<input type="text" value="${cartItem.cart_quantity}" id="quantity_1" class="qty2" name="quantity">`
                    );
                    HELPER.buildEventInputNumber(
                        inputCartQuantity,
                        1,
                        cartItem.sku_quantity
                    );
                    const numbersRow = trCartItem.find(".numbers-row");
                    if (!cartItem.is_stock_sufficient) {
                        if (cartItem.sku_quantity > 0) {
                            trCartItem.addClass("insufficient-quantity");
                            trCartItem
                                .find(".td-edit-quantity")
                                .append(
                                    `<center title="The current quantity in stock is no longer enough, please reduce the quantity or choose another product to continue shopping">The current quantity in stock is no longer enough (${cartItem.sku_quantity})</center>`
                                );
                            numbersRow.prepend(inputCartQuantity);
                        } else {
                            trCartItem
                                .find(".td-edit-quantity")
                                .append(
                                    `<center title="Out of stock, please choose another product">Out of stock, please choose another product</center>`
                                );
                            trCartItem.addClass("out-of-stock");
                            buttonCartItemUpdate.remove();
                            numbersRow.remove();
                            return trCartItem;
                        }
                    }
                    numbersRow.prepend(inputCartQuantity);
                    CART.UI.EVENT.buildEventUpdateQuantityProductInTheCart(
                        buttonCartItemUpdate,
                        cartItem.sku_id,
                        cartItem.cart_quantity,
                        inputCartQuantity
                    );
                    CART.UI.EVENT.buildEventEnabledButtonCartItemUpdate(
                        inputCartQuantity,
                        cartItem.cart_quantity,
                        buttonCartItemUpdate
                    );

                    return trCartItem;
                },
                refreshTotalMoney: () => {
                    for (const shippingMethod of CART.SHIPPING_METHOD_DATA) {
                        if (
                            shippingMethod.shipping_method_id == CART.selectId
                        ) {
                            console.log(shippingMethod);
                            CART.UI.LOAD_DATA.updateTotalMoney(shippingMethod);
                            break;
                        }
                    }
                },
                populateDataShippingMethod: (shippingMethods) => {
                    const divShippingMethod = $("#shipping-items");
                    divShippingMethod.empty();
                    shippingMethods.forEach((shippingMethod) => {
                        divShippingMethod.append(
                            CART.UI.LOAD_DATA.buildItemShippingMethod(
                                shippingMethod
                            )
                        );
                    });
                    // CART.UI.LOAD_DATA.updateTotalMoney(shippingMethods[0]);
                },
                buildItemShippingMethod: (shippingMethod) => {
                    let html = `<div class="col-4 p-1">
                                    <div class="justify-content-center">
                                        <div class="border border-dark shipping-item  p-2">
                                        <input type="radio" class="shipping-method-checkbox" name="shipping_method">
                                            <center><b>${shippingMethod.shipping_method_name}</b></center>`;
                    if (
                        shippingMethod.shipping_method_country_discount_percent !=
                            null &&
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
                    CART.UI.EVENT.buildEventSelectedShippingMethod(
                        elementShippingMethod,
                        shippingItem,
                        checkBox
                    );
                    CART.UI.EVENT.buildEventSelectedCheckboxShippingMethod(
                        checkBox,
                        shippingMethod
                    );
                    if (shippingMethod.default == true) {
                        CART.UI.EFFECT.elementShippingMethodSelected(
                            shippingItem,
                            checkBox
                        );
                    }
                    return elementShippingMethod;
                },
                updateTotalMoney: (shippingMethod) => {
                    let subtotal = 0;
                    CART.data.forEach((cartItem) => {
                        subtotal += cartItem.subtotal;
                    });
                    var shippingFee =
                        shippingMethod.shipping_method_country_expense_old_format;
                    var sale =
                        shippingMethod.shipping_method_country_discount_percent >
                        0
                            ? shippingMethod.discount_amount_format
                            : 0;
                    CART.UI.liSubtotal.html(
                        `<span>Subtotal</span> $${subtotal.toLocaleString(
                            "en-GB"
                        )}`
                    );
                    CART.UI.liShippingFee.html(
                        `<span>Shipping Fee</span> $${shippingFee}`
                    );
                    CART.UI.liSale.html(`<span>Sale</span> -$${sale}`);
                    CART.UI.liTotal.html(
                        `<span>Total</span> $${(
                            parseFloat(subtotal) +
                            parseFloat(
                                shippingMethod.shipping_method_country_expense_new
                            )
                        ).toLocaleString("en-GB")}`
                    );
                },
            },
            EVENT: {
                /**
                 * Thiết lập event xóa sản phẩm khỏi giỏ hàng
                 * @param {*} buttonCartItemRemove
                 * @param {*} skuId
                 */
                buildEventRemoveProductFromCart: (
                    buttonCartItemRemove,
                    skuId
                ) => {
                    buttonCartItemRemove.click(() => {
                        CART.LOAD.callApiRemoveProductFromCart(skuId);
                    });
                },
                /**
                 * Thiết lập event cập nhật số lượng sản phẩm trong giỏ hàng
                 * @param {*} buttonCartItemUpdate
                 * @param {*} skuId
                 * @param {*} quantity
                 * @param {*} inputCartQuantity
                 */
                buildEventUpdateQuantityProductInTheCart: (
                    buttonCartItemUpdate,
                    skuId,
                    quantity,
                    inputCartQuantity
                ) => {
                    buttonCartItemUpdate.click(() => {
                        if (inputCartQuantity.data("enabled") == true) {
                            quantity = parseInt(inputCartQuantity.val());
                            CART.LOAD.callApiUpdateQuantityProductInTheCart(
                                skuId,
                                quantity
                            );
                        }
                    });
                },
                /**
                 * Thiết lập sự kiện hiệu lực hóa nút cập nhật số lượng sản phẩm trong giỏ hàng khi thay đổi số lượng khác
                 * @param {*} inputCartQuantity
                 * @param {*} defaultQuantity
                 * @param {*} buttonUpdateQuantity
                 */
                buildEventEnabledButtonCartItemUpdate: (
                    inputCartQuantity,
                    defaultQuantity,
                    buttonUpdateQuantity
                ) => {
                    inputCartQuantity.data("quantity", defaultQuantity);
                    // console.log(defaultQuantity)
                    inputCartQuantity.data("enabled", false);
                    inputCartQuantity.on("input", function () {
                        const currentQuantity = parseInt($(this).val());
                        const defaultQuantity = parseInt(
                            $(this).data("quantity")
                        );
                        if (currentQuantity != defaultQuantity) {
                            buttonUpdateQuantity.removeClass("disabled");
                            inputCartQuantity.data("enabled", true);
                        } else {
                            buttonUpdateQuantity.addClass("disabled");
                        }
                    });
                },
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
                        CART.UI.EFFECT.elementShippingMethodSelected(
                            shippingItem,
                            checkBox
                        );
                    });
                },
                buildEventSelectedCheckboxShippingMethod: (
                    checkBox,
                    shippingMethod
                ) => {
                    checkBox.change(function () {
                        const id = $(this).data("id");
                        CART.selectId = id;
                        CART.UI.LOAD_DATA.updateTotalMoney(shippingMethod);
                        // CART.LOAD.callApiGetShippingMethod(id)
                    });
                },
                buildEventButtonCheckout: () => {
                    CART.UI.btnCheckout.click(() => {
                        const checkbox = $(
                            '[type="radio"][name="shipping_method"]:checked'
                        );
                        if (checkbox) {
                            shippingMethodId = checkbox.data("id");
                            // alert(shippingMethodId)
                            CART.LOAD.callApiBuildDataOrder(shippingMethodId);
                        }
                    });
                },
            },
            EFFECT: {
                elementShippingMethodSelected: (shippingItem, checkBox) => {
                    $(".shipping-item.selected").removeClass("selected");
                    shippingItem.addClass("selected");
                    checkBox.prop("checked", true);
                    checkBox.change();
                },
            },
        },
    };
    CART.LOAD.fetchDataCarts();
    CART.UI.EVENT.buildEventButtonCheckout();
});
