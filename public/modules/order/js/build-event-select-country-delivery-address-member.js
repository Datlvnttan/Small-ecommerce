const buildEventSelectCountryDeliveryAddress = (funcAction) => {
    selectDeliveryAddress.change(() => {
        const deliveryAddressId = selectDeliveryAddress.val();
        // alert(deliveryAddressId);
        const elementOptionSelected = selectDeliveryAddress.find(
            `option[value="${deliveryAddressId}"]:selected`
        );
        // console.log(elementOptionSelected[0])
        const countryId = elementOptionSelected.attr("country-id");
        const internationalCallingCode = elementOptionSelected.attr("international-calling-code");
        // alert(internationalCallingCode)
        const phoneNumber = elementOptionSelected.attr("phone-number");
        inputPhoneNumber.val(phoneNumber);
        // alert(countryId);
        if (countryId != undefined && typeof funcAction == "function") {
            funcAction(countryId,internationalCallingCode);
        }
    });
    selectDeliveryAddress.trigger("change")
    // alert(selectDeliveryAddress.data('country-id-default'))
    // funcAction(selectDeliveryAddress.data('country-id-default'));
};
