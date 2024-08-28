$(() => {
    const prefixDeliveryAddress = "delivery-address";
    const prefixBillingAddress = "billing-address";
    const boxDeliveryAddress = $("#box-delivery-address");
    const boxBillingAddress = $("#box-billing-address");
    const btnAddDeliveryAddress = $("#btn-add-delivery-address");
    const btnAddBillingAddress = $("#btn-add-billing-address");
    const formDataDeliveryAddress = $("#form-data-delivery-address");
    const formDataBillingAddress = $("#form-data-billing-address");
    const PARAM_ADDRESS = "address-data";
    ADDRESS_MAIN = {
        LOAD: {
            fetchDataAddress: (boxAddress, prefixAddress, formDataElement) => {
                new CallApi(route(`api.user.${prefixAddress}.index`)).all(
                    (res) => {
                        ADDRESS_MAIN.UI.LOAD_DATA.populateDataDeliveryAddress(
                            res.data,
                            boxAddress,
                            prefixAddress,
                            formDataElement
                        );
                    },
                    (res) => {}
                );
            },
            callApiCreateAddress: (
                formData,
                boxAddress,
                prefixAddress,
                formDataElement
            ) => {
                new CallApi(route(`api.user.${prefixAddress}.store`)).post(
                    formData,
                    (res) => {
                        console.log(res);
                        handleCreateToast("success", res.message, null, true);
                        ADDRESS_MAIN.LOAD.fetchDataAddress(
                            boxAddress,
                            prefixAddress,
                            formDataElement
                        );
                        $(`#modal-edit-${prefixAddress}`).modal("hide");
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("error", res.message, null, true);
                    }
                );
            },
            callApiUpdateAddress: (
                id,
                formData,
                boxAddress,
                prefixAddress,
                formDataElement
            ) => {
                new CallApi(
                    route(`api.user.${prefixAddress}.update`, { id: id })
                ).put(
                    formData,
                    (res) => {
                        handleCreateToast("success", res.message, null, true);
                        ADDRESS_MAIN.LOAD.fetchDataAddress(
                            boxAddress,
                            prefixAddress,
                            formDataElement
                        );
                        $(`#modal-edit-${prefixAddress}`).modal("hide");
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("error", res.message, null, true);
                    }
                );
            },
            callApiDeleteAddress: (
                id,
                boxAddress,
                prefixAddress,
                formDataElement
            ) => {
                new CallApi(
                    route(`api.user.${prefixAddress}.destroy`, { id: id })
                ).delete(
                    null,
                    (res) => {
                        handleCreateToast("success", res.message, null, true);
                        ADDRESS_MAIN.LOAD.fetchDataAddress(
                            boxAddress,
                            prefixAddress,
                            formDataElement
                        );
                    },
                    (res) => {
                        handleCreateToast("error", res.message, null, true);
                    }
                );
            },
            callApiSetDefaultAddress: (
                id,
                boxAddress,
                prefixAddress,
                formDataElement
            ) => {
                new CallApi(
                    route(`api.user.${prefixAddress}.setAddressDefault`, {
                        id: id,
                    })
                ).patch(
                    null,
                    null,
                    (res) => {
                        handleCreateToast("success", res.message, null, true);
                        ADDRESS_MAIN.LOAD.fetchDataAddress(
                            boxAddress,
                            prefixAddress,
                            formDataElement
                        );
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("error", res.message, null, true);
                    }
                );
            },
        },
        UI: {
            LOAD_DATA: {
                populateFormDataUpdate: (address, formDataElement) => {
                    console.log(address);
                    for (const key in address) {
                        if (Object.hasOwnProperty.call(address, key)) {
                            const value = address[key];
                            const element = formDataElement.find(`.${key}`);
                            if (element.length) {
                                if (value != null) {
                                    if (element.is('input[type="text"]')) {
                                        element.val(value);
                                    } else if (element.is("select")) {
                                        element
                                            .find(`option[value="${value}"]`)
                                            .prop("selected", true);
                                    } else {
                                        console.log(element);
                                        element.prop(
                                            "checked",
                                            address.default
                                        );
                                    }
                                }
                            }
                        }
                    }
                    formDataElement.data(PARAM_ADDRESS, address.id);
                },
                populateDataDeliveryAddress: (
                    addresses,
                    boxAddress,
                    prefixAddress,
                    formDataElement
                ) => {
                    if (addresses.length == 0) {
                        return boxAddress.html(
                            "<center><h2>No data found</h2></center>"
                        );
                    }
                    boxAddress.empty();
                    addresses.forEach((address) => {
                        ADDRESS_MAIN.UI.LOAD_DATA.buildItemAddress(
                            boxAddress,
                            address,
                            prefixAddress,
                            formDataElement
                        );
                    });
                },
                buildItemAddress: (
                    boxAddress,
                    address,
                    prefixAddress,
                    formDataElement
                ) => {
                    const itemAddress =
                        $(`<div class="item-address box-white w-100">
                        <div class="row">
                            <div class="col-lg-7 col-xxl-8">
                                <span><span class="item-address-recipient-name">${
                                    address.fullname ?? ""
                                }</span> | <span
                                        class="item-address-phone-number">\
                                        ${
                                            prefixAddress ==
                                            prefixDeliveryAddress
                                                ? address.format_phone_number ??
                                                  ""
                                                : address.tax_id_number ?? ""
                                        }
                                            </span></span><br>
                                <span class="item-address-country">${
                                    address.country
                                        ? address.country.country_name ?? ""
                                        : ""
                                }</span><br>
                                <span class="item-address-detail">${
                                    address.address_specific ?? ""
                                }</span><br>
                                <span class="item-address-info">${
                                    address.format_address ?? ""
                                }</span><br>
                                <span class="item-address-zip-code">${
                                    address.zip_code ?? ""
                                }</span>
                            </div>
                            <div class="col-lg-5 col-xxl-4 item-address-btn">
                                <div class="item-address-btn-update">
                                    <button class="btn btn-outline-danger btn-address-delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-trash" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                            <path
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                    <button class="btn btn-outline-warning btn-address-update" data-bs-toggle="modal" data-bs-target="#modal-edit-${prefixAddress}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                        <span>Update</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                        
                        

                    </div>`);
                    itemAddress.find(".btn-address-update").click(() => {
                        // alert(123);
                        // console.log(formData);
                        ADDRESS_MAIN.UI.LOAD_DATA.populateFormDataUpdate(
                            address,
                            formDataElement
                        );
                    });
                    itemAddress.find(".btn-address-delete").click(() => {
                        HELPER.messageDelete((result) => {
                            if (result.isConfirmed) {
                                ADDRESS_MAIN.LOAD.callApiDeleteAddress(
                                    address.id,
                                    boxAddress,
                                    prefixAddress,
                                    formDataElement
                                );
                            }
                        });
                    });
                    if (address.default) {
                        itemAddress.append(
                            '<div class="item-address-default">Default</div>'
                        );
                        boxAddress.prepend(itemAddress);
                    } else {
                        const btnSetDefault =
                            $(`<div class="box-btn-set-default">
                                <strong class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-check2-square" viewBox="0 0 16 16">
                                        <path
                                            d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5H3z" />
                                        <path
                                            d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                    </svg>
                                    <span>Set Default</span>
                                </strong>
                            </div>`);
                        btnSetDefault.click(() => {
                            ADDRESS_MAIN.LOAD.callApiSetDefaultAddress(
                                address.id,
                                boxAddress,
                                prefixAddress,
                                formDataElement
                            );
                        });
                        itemAddress.append(btnSetDefault);
                        boxAddress.append(itemAddress);
                    }
                    return itemAddress;
                },
            },
            EVENT: {
                buildEventBtnAddAddress: (button, formDataElement) => {
                    button.click(() => {
                        formDataElement.find('input[type="text"]').val("");
                        formDataElement.data(PARAM_ADDRESS, null);
                    });
                },
                buildEventFormDataAddress: (
                    formDataElement,
                    boxAddress,
                    prefixAddress
                ) => {
                    formDataElement.on("submit", (event) => {
                        event.preventDefault();
                        const formData = formDataElement.serializeArray();
                        const id = formDataElement.data(PARAM_ADDRESS);
                        if (id == null) {
                            ADDRESS_MAIN.LOAD.callApiCreateAddress(
                                formData,
                                boxAddress,
                                prefixAddress,
                                formDataElement
                            );
                        } else {
                            ADDRESS_MAIN.LOAD.callApiUpdateAddress(
                                id,
                                formData,
                                boxAddress,
                                prefixAddress,
                                formDataElement
                            );
                        }
                        // formData.forEach((field) => {
                        //     address[field.name] = field.value;
                        // });
                        // if (formDataElement.data(PARAM_ADDRESS)) {
                        //     address.id = formDataElement.data(PARAM_ADDRESS).id;
                        // } else {
                        // }
                        // formDataElement.find('input[type="text"]').val("");
                        // formDataElement.data(PARAM_ADDRESS, null);
                    });
                },
            },
            EFFECT: {},
        },
    };

    if (boxDeliveryAddress.length) {
        ADDRESS_MAIN.LOAD.fetchDataAddress(
            boxDeliveryAddress,
            prefixDeliveryAddress,
            formDataDeliveryAddress
        );
        ADDRESS_MAIN.UI.EVENT.buildEventBtnAddAddress(
            btnAddDeliveryAddress,
            formDataDeliveryAddress
        );
        ADDRESS_MAIN.UI.EVENT.buildEventFormDataAddress(
            formDataDeliveryAddress,
            boxDeliveryAddress,
            prefixDeliveryAddress
        );
    }

    if (boxBillingAddress.length) {
        ADDRESS_MAIN.LOAD.fetchDataAddress(
            boxBillingAddress,
            prefixBillingAddress,
            formDataBillingAddress
        );

        ADDRESS_MAIN.UI.EVENT.buildEventBtnAddAddress(
            btnAddBillingAddress,
            formDataBillingAddress
        );

        ADDRESS_MAIN.UI.EVENT.buildEventFormDataAddress(
            formDataBillingAddress,
            boxBillingAddress,
            prefixBillingAddress
        );
    }
});
