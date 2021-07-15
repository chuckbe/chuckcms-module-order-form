<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-4 chuck_ofm_product_tile" data-product-id="{{ $product->id }}" data-product-name="{{ $product->json['name'][app()->getLocale()] }}">
	<div class="thumbnail d-flex align-items-start flex-column" style="border: 1px solid #ddd;border-radius: 4px;padding:10px;height:100%;">
		@if($settings['form']['display_images'])
		<img src="{{ $product->json['featured_image'] ?? 'https://via.placeholder.com/500x333.jpg?text=No+Image+Found' }}" class="cof_productImage img-fluid" data-product-id="{{ $product->id }}" alt="">
		@endif
		<h3 class="mb-1 mt-1">{{ $product->json['name'][app()->getLocale()] }}</h3>
		@if($settings['form']['display_description'])
		<p class="mb-3">{{ $product->json['description'][app()->getLocale()] }}</p>
		@endif

		<div class="row mt-auto">
			<div class="col-sm-12" style="margin-right:15px;">
				<p class="mb-2 float-right cof_productItemPriceDisplay" data-product-id="{{ $product->id }}" data-current-price="{{ $product->json['price']['discount'] !== '0.000000' ? $product->json['price']['discount'] : $product->json['price']['final'] }}" style="font-size:16px">
					<span class="cof_productItemUnitPrice" data-product-id="{{ $product->id }}" data-product-price="{{ $product->json['price']['final'] }}" data-has-discount="{{ $product->json['price']['discount'] == '0.000000' ? 'false' : 'true' }}" @if($product->json['price']['discount'] !== '0.000000') style="text-decoration:line-through" @endif>{{ '€ ' . number_format($product->json['price']['final'], 2, ',', '.') }}</span> 
					@if($product->json['price']['discount'] !== '0.000000')
					<span style="color:red;" class="cof_productItemDiscountPrice" data-product-id="{{ $product->id }}" data-discount-price="{{ $product->json['price']['discount'] }}">{{ '€ ' . number_format($product->json['price']['discount'], 2, ',', '.') }}</span>
					@endif

				</p>
				
			</div>
			<div class="{{ count($product->json['attributes']) > 0 ? 'col-sm-12' : 'col-sm-6 col-6' }}">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<button class="btn btn-outline-secondary cof_subtractionProductBtn" data-product-id="{{ $product->id }}">-</button>
					</div>
					<input type="number" min="0" max="99" step="1" value="1" class="form-control cof_productQuantityInput" data-product-id="{{ $product->id }}" readonly style="text-align:center;padding: 0.375rem 0.5rem;">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary cof_additionProductBtn" data-product-id="{{ $product->id }}" data-q="{{ http_build_query($product->getJson('quantity'),'',',') }}">+</button>
					</div>
				</div>
			</div>
			@if(count($product->json['attributes']) > 0)
			<div class="col-sm-12">
				<div class="input-group mb-3">
					<select name="" id="" class="custom-select form-control cof_attributeSelectInput" data-product-id="{{ $product->id }}">
						<option value="" selected="true" disabled="disabled" data-option-is="false">—— Maak een keuze ——</option>
						@foreach($product->json['attributes'] as $attribute)
						<option value="{{ $attribute['name'] }}" data-attribute-name="{{ $attribute['name'] }}" data-attribute-img="{{ $attribute['image'] !== null ? URL::to('/') . $attribute['image'] : ($product->json['featured_image'] ?? 'https://via.placeholder.com/500x333.jpg?text=No+Image+Found') }}" data-attribute-price="{{ $attribute['price'] }}" data-product-id="{{ $product->id }}">{{ $attribute['name'] }}</option>
						@endforeach
					</select>
					<div class="input-group-append">
						@if(array_key_exists('options', $product->json) && count($product->json['options']) > 0 || array_key_exists('extras', $product->json) && count($product->json['extras']) > 0)
						<button class="btn btn-outline-primary cof_btnAddProductAttributeOptionsToCart" data-product-id="{{ $product->id }}" data-product-options="{{ json_encode($product->json['options']) }}" @if(array_key_exists('extras', $product->json)) data-product-extras="{{ json_encode($product->json['extras']) }}" @endif>Toevoegen</button>
						@else
						<button class="btn btn-outline-primary cof_btnAddProductAttributeToCart" data-product-id="{{ $product->id }}">Toevoegen</button>
						@endif
					</div>
				</div>
			</div>
			@else
			<div class="col-sm-6 col-6">
				<div class="input-group mb-3">
					@if(array_key_exists('options', $product->json) && count($product->json['options']) > 0 || array_key_exists('extras', $product->json) && count($product->json['extras']) > 0 )
					<button class="btn btn-outline-primary btn-block cof_btnAddProductOptionsToCart" data-product-id="{{ $product->id }}" data-product-options="{{ json_encode($product->json['options']) }}" @if(array_key_exists('extras', $product->json)) data-product-extras="{{ json_encode($product->json['extras']) }}" @endif>Toevoegen</button>
					@else
					<button class="btn btn-outline-primary btn-block cof_btnAddProductToCart" data-product-id="{{ $product->id }}">Toevoegen</button>
					@endif
				</div>
			</div>
			@endif
		</div>
	</div>
</div>