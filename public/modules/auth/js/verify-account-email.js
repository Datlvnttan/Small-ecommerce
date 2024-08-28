$(()=>{
    const btnSubmit = $("#btn_submit")
    const btnRessendEmail = $("#resend-email")
    const labelResendEmail = $("#label-resend-email");
    const labelWaitResendEmail = $("#label-wait-resend-email");
    const countdownValue = $("#countdown_value")
    const inputOTPCode = $("#otp_code")
    const errorOTPCode = $("#error_otp_code")
    const id = getParamPrefix(1)
    // alert(route("api.email-verify",{id:id}))
    window.VERIFY_EMAIL = {
        check: function(){
            new CallApi(route("api.email-verify",{id:id})).post({
                otp_code:inputOTPCode.val()
            },
                (res)=>{
                    location.replace(res.data.url)
                },(res)=>{
                    console.log(res)
                    errorOTPCode.text(res.errors ? res.errors["otp_code"][0] : res.error)
                }
            )
        },
        resend: function(){
            new CallApi(route("api.resend-email-verify",{id:id})).post(null,
                (res)=>{
                    errorOTPCode.text("")
                    countdownAllowCodeResend()
                    handleCreateToast('success',res.message,null,true)
                },(res)=>{
                    handleCreateToast('error','Your code resend request was too fast, please wait a moment to try again','error-resend-email-verify',true)
                    console.log(res)
                }
            )
        }
    }
    const countdownAllowCodeResend = ()=>{
        labelResendEmail.hide();
        labelWaitResendEmail.show();
        let seconds = 60;
        let countdown = setInterval(function() {
            seconds--;
            countdownValue.text(seconds)
            if (seconds === 0) {
                clearInterval(countdown);
                labelResendEmail.show();
                labelWaitResendEmail.hide();
            }
        }, 1000)
    }

    btnRessendEmail.click(()=>{
        window.VERIFY_EMAIL.resend()
    })
    btnSubmit.click(()=>{
        errorOTPCode.text("")
        window.VERIFY_EMAIL.check()
    });
})