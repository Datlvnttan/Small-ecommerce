const inputPassword = $("#password");
const inputEmail = $("#email");
const showPassword = $("#show-password");
const elementError = $('#error')
showPassword.click(function () {
    let type = $(this).is(":checked") ? "text" : "password";
    showPassword.attr("type", type);
});
