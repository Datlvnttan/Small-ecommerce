$(()=>{
    const btnSubmit = $("#btn_submit")
    const formRegister = $("#form-register")
    window.AUTH= {
        register: function(formData){
            new CallApi(route("api.register")).post(formData,
                (res)=>{
                    location.replace(res.data.url)
                },(res)=>{
                    console.log(res);
                    for (const key in res.errors) {
                        if (Object.hasOwnProperty.call(res.errors, key)) {
                            const error = res.errors[key];
                            $(`#error_${key}`).text(error[0]);
                        }
                        btnSubmit.attr("disabled",false)
                    }
                })
        }
    }
    btnSubmit.click(()=>{
        let formData = formRegister.serialize(); 
        console.log(formData)
        btnSubmit.attr("disabled",true)
        $('[id^="error_"]').text("");   
        AUTH.register(formData)
    })
})