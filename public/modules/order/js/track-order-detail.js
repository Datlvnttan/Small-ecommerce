const buildEventDialogConfirmOTP = (routeName,title, message,method,data,funcSuccess) => {
    Swal.fire({
        title: title,
        text: message,
        input: "text",
        inputLabel: "Enter OTP code",
        inputAttributes: {
            autocapitalize: "off",
        },
        showCancelButton: true,
        confirmButtonText: "Confirm",
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        preConfirm: async (otp) => {
            try {
                if (!otp.trim()) {
                    Swal.showValidationMessage(
                        "Please enter OTP code."
                    );
                    return false;
                }
                data['otp'] = otp;
                const url = route(routeName,data);
                return new Promise((resolve, reject) => {
                    new CallApi(url).build(
                        null,
                        (res) => {
                            resolve(res);
                        },
                        (res) => {
                            reject(res);
                            Swal.showValidationMessage(res.errors['otp'][0]);
                            resolve(false);
                        },
                        method
                    );
                });
            } catch (error) {
                Swal.showValidationMessage(`
              Request failed: ${error}
            `);
            }
        },
        allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
        if(typeof funcSuccess === 'function') {
            funcSuccess(result);
        }
    });
};
