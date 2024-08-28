
window.ADDRESS = {
    LOAD:{
        fetchDataCountry:()=>{
            
            new CallApi(route("api.shipping.country.index",{isGetLocation:1})).all((res)=>{
                console.log(res)
                console.log(ADDRESS)
                ADDRESS.UI.LOAD_DATA.populateDataCountry(res.data)
            })
        }
    },
    UI:{
        selectDeliveryCountry: $("#country_delivery_address"),
        selectBillingCountry: $("#country_billing_address"),
        selectInternationalCallingCode: $("#international_calling_code"),
        LOAD_DATA:{
            populateDataCountry:(data)=>{
                const countries = data.countries;
                const iso_code_default = data.iso_code_default
                // ADDRESS.UI.selectDeliveryCountry.empty()
                // ADDRESS.UI.selectBillingCountry.empty()
                ADDRESS.UI.selectInternationalCallingCode.empty()
                // console.log(ADDRESS.UI.selectDeliveryCountry)
                let html = '';
                let htmlInternationalCallingCode = '';
                // let selected = null;
                countries.forEach(country => {
                    let selected = country.iso_code == iso_code_default ? 'selected':'';
                    html += `<option value="${country.id}" ${selected} international-calling-code="${country.international_calling_code}" >${country.country_name}</option>`
                    htmlInternationalCallingCode += `<option value="${country.international_calling_code}">${country.country_name}(${country.international_calling_code})</option>`
                });
                if( ADDRESS.UI.selectInternationalCallingCode.length > 0){
                    ADDRESS.UI.selectInternationalCallingCode.html(htmlInternationalCallingCode)
                    ADDRESS.UI.EVENT.buildEventSelectInternationalCallingCode()
                }
                if(ADDRESS.UI.selectDeliveryCountry.length > 0){
                    ADDRESS.UI.selectDeliveryCountry.html(html)
                    ADDRESS.UI.selectDeliveryCountry.change()
                    ADDRESS.UI.selectInternationalCallingCode.find(`option[value="${iso_code_default}"]`).prop('selected', true)
                }
                if(ADDRESS.UI.selectBillingCountry.length > 0){
                    ADDRESS.UI.selectBillingCountry.html(html)
                }
            }
        },
        EVENT:{
            // buildEventSelectDeliveryCountry:()=>{
            //     ADDRESS.UI.selectDeliveryCountry.change(function () {
            //         if(ADDRESS.UI.selectInternationalCallingCode.data("change") == false)
            //         {
            //             const id = $(this).val();
            //             ADDRESS.UI.selectInternationalCallingCode.find(`option[value="${id}"]`).prop('selected', true)
            //         }
                    
            //     })
            // },
            buildEventSelectInternationalCallingCode:()=>{
                ADDRESS.UI.selectInternationalCallingCode.data("change",false)
                ADDRESS.UI.selectInternationalCallingCode.change(function () {
                    ADDRESS.UI.selectInternationalCallingCode.data("change",true)
                })
            }
        },
        EFFECT:{

        }

    }
}
ADDRESS.LOAD.fetchDataCountry()
