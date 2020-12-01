<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance:textfield;
}
.equal {
  display: flex;
  display: -webkit-flex;
  flex-wrap: wrap;
}
.legal_label {
	text-transform:none;
	font-weight: 300;
	font-style: italic;
}
.hidden, .d-none {
	display:none;
}
</style>
@include('chuckcms-module-order-form::frontend.includes.cart_icon')
<section class="section" id="cof_orderFormGlobalSection" data-site-domain="{{ URL::to('/') }}">
	<div class="container">
		<div class="row">
			@include('chuckcms-module-order-form::frontend.includes.cart_section')

			@include('chuckcms-module-order-form::frontend.includes.details_input')
		</div>
		
		@foreach(config('chuckcms-module-order-form.categories') as $catKey => $category)
			@if($category['is_displayed'])
			<div class="row equal">
				<div class="col-sm-12">
					<h4 class="mt-4">{{ $category['name'] }}</h4>
				</div>
				@foreach($products as $product)
					@if($product->json['category'] == $catKey && $product->json['is_displayed'])
						@include('chuckcms-module-order-form::frontend.includes.product_tile')
					@endif
				@endforeach
			</div>
			@endif
		@endforeach
	</div>
</section>
@include('chuckcms-module-order-form::frontend.includes.options_modal')