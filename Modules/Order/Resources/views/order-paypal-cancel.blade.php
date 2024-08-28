@php
    $user = Auth::user();
@endphp
@extends('layouts.app')
@section('css')
    <!-- SPECIFIC CSS -->
    <link href="{{ asset('modules/order/css/checkout.css') }}" rel="stylesheet">
@endsection
@section('master')
    <main class="bg_gray">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div id="confirm">
                        <div class="icon icon--order-failed svg add_bottom_15">
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
                                <g fill="none" stroke="#8EC343" stroke-width="2">
                                    <circle cx="36" cy="36" r="35"
                                        style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                                    <path d="M17.417,37.778l9.93,9.909l25.444-25.393"
                                        style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                                </g>
                            </svg> --}}
                        </div>
                        <h2>Order failed!</h2>
                        <div class="row">
                            <div class="col-4"><a class="btn btn-danger">Cancel</a></div>
                            <div class="col-4"><button id="btn_repurchase" class="btn btn-warning">Repurchase</button>
                            </div>
                            <div class="col-4"><a href="{{ route('web.order.checkout', ['key' => $checkoutKey]) }}"
                                    class="btn btn-dark">Review order</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->

    </main>
    <!--/main-->
@endsection
@section('js')
    <script>
        window.DATA = @json([
            'orderKey' => $orderKey,
            'checkoutKey' => $checkoutKey,
        ])
    </script>
    <!-- SPECIFIC SCRIPTS -->
    <script src="{{ asset('modules/order/js/order-payment-cancel.js') }}"></script>
@endsection
