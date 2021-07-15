@extends('chuckcms-module-order-form::pos.layout')
@section('content')
<div class="wrapper container-fluid p-0 d-flex" id="cof_orderFormGlobalSection" data-site-domain="{{ URL::to('/') }}">
    <div class="main col-8">
        
        @include('chuckcms-module-order-form::pos.includes.header')

        @include('chuckcms-module-order-form::pos.includes.category_section')
        @include('chuckcms-module-order-form::pos.includes.product_section')
        
        @include('chuckcms-module-order-form::pos.includes.handler_section', ['settings' => $settings])
        
    </div>
    <div class="bestelling col-4">
        @include('chuckcms-module-order-form::pos.includes.cart_section', ['settings' => $settings])
    </div>
</div>
@include('chuckcms-module-order-form::pos.includes.options_modal')
@endsection

@section('scripts')
@include('chuckcms-module-order-form::pos.scripts')
@endsection