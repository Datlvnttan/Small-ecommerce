@php
    $user = Auth::user();
@endphp

@extends('Layouts.setup')

@section('setup-css')
    <link href="{{ asset('library/css/sidebar-manager.css') }}" rel="stylesheet">
    
    {{-- <link href="{{ asset('library/css/style.css') }}" rel="stylesheet"> --}}
{{-- 
    <!-- SPECIFIC CSS -->
    <link href="{{ asset('library/css/account.css') }}" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    {{-- <link href="{{ asset('library/css/custom.css') }}" rel="stylesheet">  --}}

    <!-- LOADING SPINNER CSS -->
    {{-- <link href="{{ asset('library/css/loading-spinner.css') }}" rel="stylesheet"> --}}
    @yield('css')
@endsection
@section('setup-content')
    <div id="page">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="/" class="logo">
                <i class='bx bx-code-alt'></i>
                <div class="logo-name"><span>Ecom</span>merce</div>
            </a>
            @yield('sidebar-menu')
            <ul class="side-menu p-0">
                <li>
                    <a href="{{ route('web.auth.logout') }}" class="logout">
                        <i class='bx ti-power-off'></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
        <!-- End of Sidebar -->
        <!-- Main Content -->
        <div class="content">
            <!-- Navbar -->
            <nav>
                <i class='ti-menu menu-icon'></i>
                <form>
                    <div class="form-input">
                        <input type="search" placeholder="Search...">
                        <button class="search-btn" type="submit"><i class='ti-search'></i></button>
                    </div>
                </form>
                <input type="checkbox" id="theme-toggle" hidden>
                <label for="theme-toggle" class="theme-toggle"></label>
                <script src="{{ asset('library/js/change-theme.js') }}"></script>
                <a href="#" class="notif">
                    <i class='bx bx-bell'></i>
                    <span class="count">12</span>
                </a>
                <a href="" class="profile">
                    <img src="{{ asset('img/user.png') }}">
                    Lê Phát Đạt
                </a>
            </nav>
            <main class="container">
                <div id="page">
                    <section>
                        @yield('manager-content')
                    </section>
                </div>
            </main>
        </div>
        <!--End of Main Content -->
    </div>
@endsection
@section('setup-js')
<script src="{{ asset('library/js/bootstrap.js') }}"></script>
    <script src="{{ asset('library/js/sidebar-manager.js') }}"></script>
    {{-- <!-- COMMON SCRIPTS -->
    <script src="{{ asset('library/js/common_scripts.min.js') }}"></script>
    <script src="{{ asset('library/js/main.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Client type Panel
        $('input[name="client_type"]').on("click", function() {
            var inputValue = $(this).attr("value");
            var targetBox = $("." + inputValue);
            $(".box").not(targetBox).hide();
            $(targetBox).show();
        });
    </script> --}}
    @yield('js')
@endsection

