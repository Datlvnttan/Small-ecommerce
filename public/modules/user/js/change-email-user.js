$(()=>{
    const formPasswordEntry = $('#form-password-entry')
    console.log(formPasswordEntry)
    formPasswordEntry.on('submit',function(ev){
        ev.preventDefault()
        const formData = $(this).serializeArray();
        new CallApi(route(window.DATA.routeSubmit,{
            password:inputPassword.val(),
            email:inputEmail.val(),
            tokenChangeEmail:window.DATA.tokenChangeEmail,
        })).post(null,(res)=>{
            location.replace(res.data)
        },(res)=>{
            console.log(res)
            elementError.text(res.error)
            handleCreateToast('error',res.error??res.message,null,true)
        })
        return true
    })
})