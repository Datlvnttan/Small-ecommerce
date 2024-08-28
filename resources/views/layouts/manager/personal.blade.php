@extends('layouts.manager.layout-manager')

@section('sidebar-menu')
    <ul class="side-menu p-0">                                     
        <li class="{{request()->is('personal/profile') ? 'active' :'' }} "><a href="{{route('web.personal.profile')}}"><i class='bx ti-settings'></i>Profile</a></li> 
        <li class="{{request()->is('personal/address') ? 'active' :'' }} "><a href="{{route('web.personal.address')}}"><i class='bx ti-location-pin' ></i>Addresses</a></li> 
        <li class="{{request()->is('personal/orders') ? 'active' :'' }} "  id="menu-orders-history"><a href="{{route('web.order.personal.order.index')}}"><i class='bx ti-package'></i>Order Histore</a></li>
    </ul>
@endsection

@section('manager-content')
    @yield('personal-content')
@endsection

