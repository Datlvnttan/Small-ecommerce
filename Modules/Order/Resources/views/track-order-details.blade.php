@php
    $user = Auth::user();
@endphp
@extends('layouts.app')
@section('css')
    <!-- SPECIFIC CSS -->
@endsection
@section('master')
<main class="container">
    <div id="page">
        <section>
            @include('order::components.order.order-detail')
        </section>
    </div>
</main>
    {{-- <main class="bg_gray">
        
    </main> --}}
@endsection
@section('js')
    {{-- <!-- SPECIFIC SCRIPTS -->
    <script src="{{ asset('library/js/carousel_with_thumbs.js') }}"></script> --}}
    <script src="{{ asset('modules/order/js/track-order-detail.js') }}"></script>
    <script src="{{ asset('modules/order/shared/js/order-show.js') }}"></script>
@endsection
