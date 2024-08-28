


@extends('layouts.app')
@section('master')
	<!-- SPECIFIC CSS -->
    <link href="{{asset('library/css/home_1.css')}}" rel="stylesheet">
<main>
        @yield('content')
    
</main>
    <script src="{{asset('library/js/carousel-home.min.js')}}"></script>
    <script src="{{asset('library/js/jquery.cookiebar.js')}}"></script>
	{{-- <script>
		$(document).ready(function() {
			'use strict';
			$.cookieBar({
				fixed: true
			});
		});
	</script> --}}
@endsection