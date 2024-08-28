window.AUTH = {
    login: function (email, password) {
        $(".error_validation").each(function () {
            $(this).text("");
        });
        new CallApi(route("api.login")).post(
            {
                email: email,
                password: password,
            },
            (res) => {
                var d = new Date();
                d.setTime(d.getTime() + res.data.expires_in * 60 * 2000);
                var expires = "expires=" + d.toUTCString();
                document.cookie = `${"token"}=${
                    res.data.access_token
                }; expires=${expires}; path=/; samesite=strict; secure`;
                location.replace(res.data.url);
            },
            (res) => {
                console.log(res);
                if (res.error) {
                    $("#error_validation").text(res.error);
                }
                for (const key in res.errors) {
                    if (res.errors.hasOwnProperty(key)) {
                        $(`#error_${key}`).text(res.errors[key][0]);
                    }
                }
            }
        );
    },
    loginSocial: function (driveValue) {
        new CallApi(route("api.login.social", { drive: driveValue })).get(
            (res) => {
                location.replace(res.data.url);
            }
        );
    },
};
$(() => {
    const btnSubmit = $("#btn_submit");
    const inputEmail = $("#email");
    const inputPassword = $("#password_in");
    const btnLoginGoogle = $("#login_google");
    btnSubmit.click(() => {
        AUTH.login(inputEmail.val(), inputPassword.val());
    });
    btnLoginGoogle.click(() => {
        AUTH.loginSocial("google");
    });
});
