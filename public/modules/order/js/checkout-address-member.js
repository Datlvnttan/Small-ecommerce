const routeDeliveryAddressIndex = "api.user.delivery-address.index";
const routeBillingAddressIndex = "api.user.billing-address.index";
const selectDeliveryAddress = $(`#delivery_address`);
const selectBillingAddress = $(`#billing_address`);
const otherAddr = $("#other_addr");
const inputPhoneNumber = $("#phone_number");
window.CHECKOUT_ADDRESS = {
    LOAD: {
        /**
         * gọi api để đỗ dữ liệu vào select address
         * @param {*} routeName
         * @param {*} funcSuccess
         */
        fetchDataAddress: (routeName, funcSuccess) => {
            new CallApi(route(routeName)).get(
                (res) => {
                    if (typeof funcSuccess === "function") {
                        funcSuccess(res.data);
                    }
                },
                (res) => {
                    // $('main').html(`<h1>${res.message}</h1>`)
                }
            );
        },
    },
    UI: {
        LOAD_DATA: {
            populateDataAddress: (selectAddress, addresses, func_buildItem) => {
                // const selectAddress = $(`#${id}`);
                // console.log(selectAddress);
                if (addresses.length == 0) {
                }
                selectAddress.empty();
                addresses.forEach((address) => {
                    const optionAddress = func_buildItem(address);
                    // console.log(optionAddress)
                    selectAddress.append(optionAddress);
                });
                selectAddress.trigger("change");
            },
            buildItemDeliveryAddress: (deliveryAddress) => {
                let option = `${deliveryAddress.fullname} - ${deliveryAddress.address_specific}, ${deliveryAddress.ward}, ${deliveryAddress.district}, ${deliveryAddress.province} - ${deliveryAddress.zip_code}, ${deliveryAddress.country.country_name}`;
                const elementOption = $(
                    `<option value="${deliveryAddress.id}" ${
                        deliveryAddress.default ? "selected" : ""
                    } title="${option}" >${option}</option>`
                );
                elementOption.attr("country-id", deliveryAddress.country_id);
                elementOption.attr("international-calling-code", deliveryAddress.international_calling_code);
                
                elementOption.attr(
                    "phone-number",
                    deliveryAddress.phone_number
                );
                // elementOption.data("obj", JSON.stringify(deliveryAddress));
                return elementOption;
            },
            buildItemBillingAddress: (billingAddress) => {
                let option = `${billingAddress.fullname} - ${billingAddress.address_specific}, ${billingAddress.ward}, ${billingAddress.district}, ${billingAddress.province} - ${billingAddress.zip_code}, ${billingAddress.country.country_name}`;
                return $(
                    `<option value="${billingAddress.id}" ${
                        billingAddress.default ? "selected" : ""
                    } title="${option}" >${option}</option>`
                );
            },
        },
        EVENT: {
            createEventCheckboxotherAddr: () => {
                const boxSelectBillingAddress = $("#box-select-billing-address")
                otherAddr.change(function () {
                    CHECKOUT_ADDRESS.UI.EFFECT.loadSelectBillingAddress(boxSelectBillingAddress);
                });
            },
        },
        EFFECT: {
            loadSelectBillingAddress: (boxSelectBillingAddress) => {
                if (otherAddr.is(":checked") == true) {
                    selectBillingAddress.prop("disabled", false);
                    boxSelectBillingAddress.slideDown();
                } else {
                    selectBillingAddress.prop("disabled", true);
                    boxSelectBillingAddress.slideUp();
                }
            },
        },
    },
    // CHECKOUT_ADDRESS.LOAD.fetchDataAddress('api.user.delivery-address.index');

    // 'api.user.delivery-address.index'
};
CHECKOUT_ADDRESS.LOAD.fetchDataAddress(routeDeliveryAddressIndex, (data) => {
    CHECKOUT_ADDRESS.UI.LOAD_DATA.populateDataAddress(
        selectDeliveryAddress,
        data,
        (item) => {
            return CHECKOUT_ADDRESS.UI.LOAD_DATA.buildItemDeliveryAddress(item);
        }
    );
});
CHECKOUT_ADDRESS.LOAD.fetchDataAddress(routeBillingAddressIndex, (data) => {
    CHECKOUT_ADDRESS.UI.LOAD_DATA.populateDataAddress(
        selectBillingAddress,
        data,
        (item) => {
            return CHECKOUT_ADDRESS.UI.LOAD_DATA.buildItemBillingAddress(item);
        }
    );
});
CHECKOUT_ADDRESS.UI.EVENT.createEventCheckboxotherAddr();
CHECKOUT_ADDRESS.UI.EFFECT.loadSelectBillingAddress();
// alert(route('api.user.delivery-address.index'))
