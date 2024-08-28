@extends('layouts.app')
@section('master')
    <!-- /page_header -->
		<div class="row justify-content-center">
			<div class="col-xl-6 col-lg-6 col-md-8">
				<div class="box_account">
					<h3 class="client">Error verify account email</h3>
					<div class="form_container">
						<div>
							<center>
                                <h2>{{$error}}</h2>
                                <span><a href="{{route('web.home.index')}}">Click here</a> to return to the home page</span>
                            </center>
						</div>
					</div>
					<!-- /form_container -->
				</div>
				<!-- /box_account -->
			</div>
		</div>
		<!-- /row -->
	<script src="{{asset("modules/auth/js/verify-account-email.js")}}"></script>
@endsection
