@extends('chuckcms-module-order-form::pos.layout')
@section('content')

{{-- <section class="section" id="cof_orderFormGlobalSection" data-site-domain="{{ URL::to('/') }}">
    <div class="container">
        <div class="row">
            @include('chuckcms-module-order-form::frontend.includes.cart_section', ['settings' => $settings])

            @include('chuckcms-module-order-form::frontend.includes.details_input')
        </div>
        
        @foreach(ChuckRepeater::for(config('chuckcms-module-order-form.categories.slug')) as $category)
            @if($category->is_displayed)
            <div class="row equal">
                <div class="col-sm-12">
                    <h4 class="mt-4">{{ $category->name }}</h4>
                </div>
                @foreach($products as $product)
                    @if($product->json['category'] == $category->id && $product->json['is_displayed'])
                        @include('chuckcms-module-order-form::frontend.includes.product_tile', ['settings' => $settings])
                    @endif
                @endforeach
            </div>
            @endif
        @endforeach
    </div>
</section>
 --}}

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