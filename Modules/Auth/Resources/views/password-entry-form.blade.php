@extends('layouts.app')
@section('master')
    <!-- /page_header -->
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-8">
            <div class="box_account">
                <h3 class="client">You are making a request that requires authentication</h3>
                <div class="form_container">
                    <form id="form-password-entry">
                        <div class="form-group">
                            <p>Please enter your password to continue</p>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                            <input type="email" class="form-control" hidden name="email" id="email"
                                value="{{ $email }}" id="email" placeholder="Email">
                            <div class="box-validation">
                                <span class="validation" id="validate_password"></span>
                            </div>
                            <h6 style="color: red" id="error"></h6>
                            {{-- @if (isset($data))
                                @foreach ($data as $key => $value)
                                    <input type="text" class="form-control" name="{{ $key }}"
                                        id="{{ $key }}" value="{{ $value }}" hidden>
                                @endforeach
                            @endif --}}
                            <br>
                            <input type="checkbox" id="show-pasword">
                            <label for="show-pasword">Show password</label>
                            <br>
                            <center><button type="submit" class="btn btn-primary btn-block" id="btn-confirm">Confirm</button></center>
                        </div>
                    </form>
                </div>
                <!-- /form_container -->
            </div>
            <!-- /box_account -->
        </div>
    </div>
    <!-- /row -->
@endsection
@section('js')
    <script src="{{ asset('modules/auth/js/password-entry-form.js') }}"></script>
    @if (isset($jsPath))
        <script src="{{ asset($jsPath) }}"></script>
    @endif
    <script>
        window.DATA = @json($data)
    </script>
@endsection
