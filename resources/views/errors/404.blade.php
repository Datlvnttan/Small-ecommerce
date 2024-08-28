@extends('layouts.app')
@section('master')
    <main class="bg_gray">
        <div id="error_page">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-xl-7 col-lg-9">
                        <img src="img/404.svg" alt="" class="img-fluid" width="400" height="212">
                        <h1>{{$title ?? 'Error! An error occurred.'}}</h1>
                        {{-- <p>The page you're looking is not founded!</p> --}}
                        <p>{{$error ?? '404'}}</p>
                        {{-- <form>
                            <div class="search_bar">
                                <input type="text" class="form-control" placeholder="What are you looking for?">
                                <input type="submit" value="Search">
                            </div>
                        </form> --}}
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /error_page -->
    </main>
@endsection
