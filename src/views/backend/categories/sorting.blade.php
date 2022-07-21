@extends('chuckcms::backend.layouts.base')

@section('title')
Categorie: {{ $category->name }} sorteren
@endsection

@section('css')
@endsection

@section('scripts')
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
<script>
$( document ).ready(function() { 

	$( "#products_container_wrapper" ).sortable({
	    revert: true,
	    forcePlaceholderSize: true,
	    deactivate: function( event, ui ) {
	    	sortAndUpdate().done(function(data) {

		        if (data.status == "success"){
		            swal({
						showCancelButton: false,
						showConfirmButton: false,
						text: "Categorie gesorteerd!",
						timer: 2000,
					});
		        } else{
		            swal({
						icon: 'error',
						title: 'Error...',
						text: 'Er is iets misgegaan!',
						timer: 3000,
					});
		        }

		    });
	    }
  	});

  	function sortAndUpdate() {
  		let order = 1;
  		let product_order = [];

  		$('.product_sort_card').each(function() {
  			$(this).attr('data-order', order);
  			product_order.push($(this).attr('data-product'));
  			order++;
  		});

  		return $.ajax({
	        method: 'POST',
	        url: "{{ route('dashboard.module.order_form.categories.update_sort', ['category' => $category->id]) }}",
	        data: { 
	            products: product_order,
	            _token: "{{ Session::token() }}"
	        }
	    });
  	}

});
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.module.order_form.categories.index') }}">Categorieën</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Categorie: {{ $category->name }} sorteren</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12">
    		<h6 class="text-underline">Sorteer producten voor categorie: {{ $category->name }}</h6>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="row" id="products_container_wrapper">
        		@php $featuredLocale = ChuckSite::getFeaturedLocale(); @endphp
	        	@foreach($products->sortBy('json.order') as $product)
	        	<div class="col-sm-3 product_sort_card" data-order="{{ $loop->iteration }}" data-product="{{ $product->id }}">
	        		<div class="bg-white shadow-sm rounded py-3 px-3 mx-2 mb-2">
		        		<small>{{ $product->json['name'][$featuredLocale] }}</small>
	        		</div>
	        	</div>
	        	@endforeach
        	</div>
        </div>
    </div>
</div>
@endsection