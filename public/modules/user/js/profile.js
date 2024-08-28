$(() => {
    const information_inputs = $(".information-input.readonly");
    const btn_update = $(".btn-update");
    const update_data_information = $("#update-data-information");
    const formProfile = $("#form-profile");

    const input_username = $("#input-username");
    const input_full_name = $("#input-full-name");
    const input_phone_number = $("#input-phone-number");
    const input_email = $("#input-email");
    const input_date_of_birth = $("#input-date-of-birth");
    const radioGenderMale = $("#radio-gender-male");
    const radioGenderFemale = $("#radio-gender-female");
    const input_old_password = $("#input-old-password");
    const input_new_password = $("#input-new-password");
    const input_new_password_confirmation = $(
        "#input-new-password-confirmation"
    );
    const formChangePassword = $("#form-change-password");
    window.PROFILE = {
        LOAD: {
            fetchDataProfile: () => {
                new CallApi(route("api.user.profile")).get((res) => {
                    console.log(res);
                    PROFILE.UI.LOAD_DATA.populateDataProfile(res.data);
                });
            },
            updateDataProfile: (data) => {
                new CallApi(route("api.user.profile")).put(
                    data,
                    (res) => {
                        console.log(res);

                        PROFILE.UI.LOAD_DATA.setInputReadonly(true);
                        btn_update.text("Change");
                        btn_update.addClass("btn-outline-dark");
                        btn_update.removeClass("btn-warning");
                        update_data_information.prop("checked", false);
                        handleCreateToast("success", res.message, null, true);
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("error", res.message, null, true);
                    }
                );
            },
            changePassword: (data) => {
                console.log(data);
                new CallApi(route("api.user.profile.changePassword")).patch(
                    null,
                    data,
                    (res) => {
                        console.log(res);
                        handleCreateToast("success", res.message, null, true);
                        $('#modal-change-password').modal('hide')
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
                populateDataProfile: (data) => {
                    input_username.val(data.nickname);
                    input_full_name.val(data.fullname);

                    input_email.val(data.email);
                    // alert(HELPER.convertDateToString(data.birthday))
                    input_date_of_birth.val(
                        data.birthday
                    );
                    if (data.phone_number) {
                        input_phone_number.val(data.phone_number);
                    }
                    if (data.gender) {
                        $(
                            `input[name="gender"][type="radio"][value="${data.gender}"]`
                        ).prop("checked", true);
                    }
                },
                setInputReadonly: (boolValue) => {
                    information_inputs.each(function () {
                        $(this).attr("readonly", boolValue);
                        return boolValue
                            ? $(this).removeClass("information-input-hover")
                            : $(this).addClass("information-input-hover");
                    });
                    $("input[type='radio']").each(function () {
                        $(this).attr("disabled", boolValue);
                    });
                },
            },
            EVENT: {},
            EFFECT: {},
        },
    };

    update_data_information.change(function () {
        console.log($(this).is(":checked"));
        if ($(this).is(":checked")) {
            PROFILE.UI.LOAD_DATA.setInputReadonly(false);
            btn_update.text("Save");
            btn_update.removeClass("btn-outline-dark");
            btn_update.addClass("btn-warning");
        } else {
            // const formData = $(formProfile).serializeArray();
            const formData = {
                nickname:input_username.val(),
                fullname:input_full_name.val(),
                email:input_email.val(),
            }
            if(input_date_of_birth.val() != null && input_date_of_birth.val() != '')
            {
                formData['birthday'] = input_date_of_birth.val();
            }
            if(input_phone_number.val()!= null && input_phone_number.val()!= '')
            {
                formData['phoneNumber'] = input_phone_number.val();
            }
            const radioGender = $("input[name='gender']:checked")
            if (radioGender.length > 0) {
                formData["gender"] = radioGender.val();
            }
            console.log(formData);
            PROFILE.LOAD.updateDataProfile(formData);
        }
    });
    formChangePassword.on("submit", function (ev) {
        ev.preventDefault();
        const formData = $(this).serializeArray();
        PROFILE.LOAD.changePassword(formData);
    });
    input_old_password.on("input", function () {
        $(this).val($(this).val().trim());
    });
    input_new_password.on("input", function () {
        $(this).val($(this).val().trim());
    });
    input_new_password_confirmation.on("input", function () {
        $(this).val($(this).val().trim());
    });

    $("#show-password").click(function () {
        let type = $(this).is(":checked") ? "text" : "password";
        $(".information-input.password").each(function () {
            $(this).attr("type", type);
        });
    });

    PROFILE.UI.LOAD_DATA.setInputReadonly(true);

    PROFILE.LOAD.fetchDataProfile();
});
