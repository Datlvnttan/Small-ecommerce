


@extends('layouts.app')
@section('master')
<main class="bg_gray">
		
	<div class="container margin_30">
		<div class="page_header">
			<div class="breadcrumbs">
				<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Auth</a></li>
					<li>@yield('page')</li>
				</ul>
		</div>
		<h1>Sign In or Create an Account</h1>
	</div>
	    
		</div>
		<!-- /container -->
        @yield('content')
    
    </main>
@endsection