@extends('layouts.app')
@section('master')
    <!-- /page_header -->
		<div class="row justify-content-center">
			<div class="col-xl-6 col-lg-6 col-md-8">
				<div class="box_account">
					<h3 class="client">Verify account email</h3>
					<div class="form_container">
						<div>
							<div class="form-group">
								<p>Please enter the OTP code sent to your email</p>
								<input type="text" class="form-control" name="otp_code" id="otp_code" placeholder="OTP code">
								<span class="error_validation" id="error_otp_code"></span>
							</div>
							<p id="label-resend-email">Haven't received the code yet? <a style="color: red; cursor: pointer;" id="resend-email">Click here</a> to resend the code</p>
							<p id="label-wait-resend-email" style="display: none;">Please wait another <span style="color: red;"><span id="countdown_value">60</span>s</span> to resend the code</p>
							<div class="text-center"><input type="submit" value="Confirm" class="btn_1" id="btn_submit"></div>
						</div>
					</div>
					<!-- /form_container -->
				</div>
				<!-- /box_account -->
				<div class="row">
					<div class="col-md-6 d-none d-lg-block">
						<ul class="list_ok">
							<li>Find Locations</li>
							<li>Quality Location check</li>
							<li>Data Protection</li>
						</ul>
					</div>
					<div class="col-md-6 d-none d-lg-block">
						<ul class="list_ok">
							<li>Secure Payments</li>
							<li>H24 Support</li>
						</ul>
					</div>
				</div>
				<!-- /row -->
			</div>
		</div>
		<!-- /row -->
	<script src="{{asset("modules/auth/js/verify-account-email.js")}}"></script>
@endsection
