@extends('Layouts.manager.personal')
@section('css')
    <link rel="stylesheet" href="{{ asset('modules/user/css/profile.css') }}">
@endsection
@section('personal-content')
    <div class="container-profile">
        <form class="box-white box-info" id="form-profile">
            <h4>Account Information</h4>
            <hr>
            <div class="row p-0">
                <div class="col-lg-6">
                    <div class="box-information-input">
                        <label for="input-username" class="information-label">Nick Name</label>
                        <input id="input-username" class="information-input readonly" name="nickname" type="text"
                            placeholder="Nick Name">
                        <div class="box-validation">
                            <span class="validation" id="validate_nickname"></span>
                        </div>

                    </div>
                    <div class="box-information-input">
                        <label for="input-full-name" class="information-label">Full Name</label>
                        <input id="input-full-name" class="information-input readonly" type="text" name="fullname"
                            placeholder="Full Name">
                        <div class="box-validation">
                            <span class="validation" id="validate_fullname"></span>
                        </div>
                    </div>
                    <div class="box-information-input">
                        <label for="input-email" class="information-label">Email</label>
                        <input id="input-email" class="information-input readonly" type="Email" name="email"
                            placeholder="email@exampo.com">
                        <div class="box-validation">
                            <span class="validation" id="validate_email"></span>
                        </div>
                    </div>
                    <div class="box-information-input">
                        <label for="input-phone-number" class="information-label">Phone Number</label>
                        <input id="input-phone-number" maxlength="10" class="information-input readonly" type="text"
                            name="phoneNumber" placeholder="Phone Number" maxlength="10">
                        <div class="box-validation">
                            <span class="validation" id="validate_phoneNumber"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box-information-input">
                        <label for="input-date-of-birth" class="information-label">Birthday</label>
                        <input id="input-date-of-birth" class="information-input" type="date" value="" name="birthday"
                            placeholder="dd/MM/yyyy">
                        <div class="box-validation">
                            <span class="validation" id="validate_birthday"></span>
                        </div>
                    </div>
                    <div class="box-information-input">
                        <label class="information-label">Gender</label>
                        <div class="information-input ">
                            <div class="item-gender">
                                <input type="radio" value="Male" id="radio-gender-male" name="gender" disabled>
                                <label for="radio-gender-male">Male</label>
                            </div>
                            <div class="item-gender">
                                <input type="radio" value="Female" id="radio-gender-female" name="gender" disabled>
                                <label for="radio-gender-female">Female</label>
                            </div>
                        </div>
                        <div class="box-validation">
                            <span class="validation" id="validate_gender"></span>
                        </div>
                    </div>
                    <div class="box-information-input">
                        <label class="information-label">Password</label>
                        <div class="information-input information-input-don-hover">
                            <div id="input-password">**********</div>
                            <a class="btn-update-password" data-bs-toggle="modal"
                                data-bs-target="#modal-change-password">Change</a>
                        </div>
                        <div class="box-validation">
                            
                        </div>
                    </div>
                    <div class="box-information-input">
                        <label class="information-label">Joining Date</label>
                        <div id="input-joining-date" class="information-input information-input-don-hover">24/04/2023</div>
                    </div>
                </div>
            </div>
            <div class="box-button">
                <input type="checkbox" name="update-data" id="update-data-information" hidden>
                <label class="btn-update btn btn-outline-dark" for="update-data-information">Change</label>
            </div>
        </form>
        
        
    </div>
    



    <div class="modal fade" id="modal-change-password" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content   box-change-password">
                <div class="modal-header">
                    <center>Change Password</center>
                    <button type="button" class="btn-close" id="btn-modal-change-password-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="post" id="form-change-password">
                    <div class="modal-body" style="height: 350px;">
                        <div class="box-information-input">
                            <label for="input-old-password" class="information-label">Old password</label>
                            <input id="input-old-password" maxlength="10" class="information-input password"
                                type="password" name="oldPassword" placeholder="Old password">
                            <div class="box-validation">
                                <span class="validation" id="validate_oldPassword"></span>
                            </div>
                        </div>
                        <div class="box-information-input">
                            <label for="input-new-password" class="information-label">New Password</label>
                            <input id="input-new-password" maxlength="10" class="information-input password"
                                type="password" name="newPassword" placeholder="New Password">
                            <div class="box-validation">
                                <span class="validation" id="validate_newPassword"></span>
                            </div>
                        </div>
                        <div class="box-information-input">
                            <label for="input-new-password-confirmation" class="information-label">Re-enter new
                                password</label>
                            <input id="input-new-password-confirmation" maxlength="10" class="information-input password"
                                type="password" name="newPassword_confirmation" placeholder="Re-enter new password">
                                <div class="box-validation">
                                    <span class="validation" id="validate_newPassword_confirmation"></span>
                                </div>
                        </div>
                        <div class="box-show-password">
                            <input type="checkbox" id="show-password">
                            <label for="show-password">Hiện password</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('modules/user/js/profile.js') }}"></script>
@endsection
