@php
    $user = Auth::user();
@endphp
@extends('layouts.app')
@section('css')
    <!-- SPECIFIC CSS -->
    <link href="{{ asset('modules/order/css/error_track.css') }}" rel="stylesheet">
@endsection
@section('master')
    <main class="bg_gray">
        <div id="track_order">
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="col-xl-7 col-lg-9">
						<img src="{{asset('img/track_order.svg')}}" alt="" class="img-fluid add_bottom_15" width="200" height="177">
						<p>Quick Tracking Order</p>
						<form action="{{route('web.order.findTrackOrder')}}" method="GET">
							<div class="search_bar">
								<input type="text" class="form-control" name="orderKey" placeholder="Invoice ID">
								<input type="submit" value="Search">
							</div>
						</form>
                        @if (isset($error))
                            <h1 style="color: red">{{$error}}</h1>
                        @endif
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /track_order -->
    </main>
@endsection
@section('js')
    {{-- <!-- SPECIFIC SCRIPTS -->
    <script src="{{ asset('library/js/carousel_with_thumbs.js') }}"></script> --}}

    <script src="{{ asset('modules/order/js/track-order.js') }}"></script>
@endsection
