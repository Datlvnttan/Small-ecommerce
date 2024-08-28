@extends('Layouts.manager.personal')
@section('css')
    <link href="{{ asset('library/css/tab.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/order/manager/personal/css/order.css') }}">
@endsection
@section('personal-content')
    <div >
        <div class="header">
            <div class="left">
                <h1>Orders</h1>
                <ul class="breadcrumb">
                  
                </ul>
            </div>
           
        </div>
        <div class="card">

            <div class="card-body" id="scroll-top">
                <ul class="navbar">
                    <li>
                        <a class="tab active" data-id="all">
                            <span class="icon"><i class='ti-home-alt-2'></i></span>
                            <span class="text">All</span>
                        </a>
                    </li>
                    @foreach($orderStatuses as $orderStatus)
                    <li>
                        <a class="tab" data-id="{{$orderStatus->name}}" data="{{$orderStatus->value}}">
                            <span class="icon"><i class='ti-dice-3'></i></span>
                            <span class="text">{{$orderStatus->value}}</span>
                        </a>
                    </li>
                    @endforeach 
                </ul>

                <div class="tab-content" style="min-height: 600px;">
                    <div class="tab-pane active box-my-short-links" id="all">
                    </div>
                    @foreach($orderStatuses as $orderStatus)
                    <div class="tab-pane box-my-short-links" id="{{$orderStatus->name}}">
                    </div>
                    @endforeach 
                </div>
                {{-- <div style="padding: 20px;">
                    
                </div> --}}
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="{{ asset('library/js/tab.js') }}"></script>
<script src="{{ asset('modules/order/manager/personal/js/order.js') }}"></script>
@endsection