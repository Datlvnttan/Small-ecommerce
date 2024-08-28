const buildEventSelectCountryDeliveryAddress = (funcAction)=>{
    ADDRESS.UI.selectDeliveryCountry.change(()=>{
        const countryId = ADDRESS.UI.selectDeliveryCountry.val();
        const elementOptionSelected = ADDRESS.UI.selectDeliveryCountry.find(
            `option[value="${countryId}"]:selected`
        );
        // console.log(elementOptionSelected[0])
        const internationalCallingCode = elementOptionSelected.attr("international-calling-code");
        // alert(internationalCallingCode)
        if(typeof funcAction == "function")
        {
            funcAction(countryId,internationalCallingCode)
        }
    })
    ADDRESS.UI.selectDeliveryCountry.change()
    // alert(12)

}