@extends('auth::layouts.master')
@section('page')
    register
@endsection
@section('content')
    <!-- /page_header -->
		<div class="row justify-content-center">
			<div class="col-xl-6 col-lg-6 col-md-8">
				<div class="box_account">
					<h3 class="new_client">Register</h3> <small class="float-right pt-2">* Required Fields</small>
					<div class="form_container">
						<form id="form-register">
							<div class="form-group">
								<input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full name*">
								<span class="error_validation" id="error_fullname"> </span>
							</div>
							<div class="form-group">
								<input type="email" class="form-control" name="email" id="email" placeholder="Email*">
								<span class="error_validation" id="error_email"> </span>
							</div>
							<div class="form-group">
								<input type="password" class="form-control" name="password" id="password" value="" placeholder="Password*">
								<span class="error_validation" id="error_password"> </span>
							</div>
							<div class="form-group">
								<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="" placeholder="Confirm Password*">
								<span class="error_validation" id="error_password_confirmation"> </span>
							</div>
						</form>
						<hr>
							
						</div>
					
						<!-- /company -->
						<hr>
						{{-- <div class="form-group">
							<label class="container_check">Accept <a href="#0">Terms and conditions</a>
								<input type="checkbox" id="check_accept_conditions">
								<span class="checkmark"></span>
							</label>
						</div> --}}
						<div class="text-center"><input type="submit" value="Register" class="btn_1 full-width" id="btn_submit"></div>
					</div>
					<!-- /form_container -->
				</div>
				<!-- /box_account -->
			</div>
		</div>
		<!-- /row -->
		<script src="{{asset("modules/auth/js/register.js")}}"></script>
@endsection
