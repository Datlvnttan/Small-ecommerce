@extends('Layouts.manager.personal')
@section('css')
    
   
@endsection
@section('personal-content')
    @include('order::components.order.order-detail')
@endsection
@section('js')
    <script src="{{ asset('modules/order/shared/js/order-show.js') }}"></script>
    <script src="{{ asset('modules/order/manager/personal/js/order-show.js') }}"></script>
    {{-- <script>
        document.getElementById('').class = 'active'
    </script> --}}
@endsection
