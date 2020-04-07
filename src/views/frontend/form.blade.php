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
</style>
@include('chuckcms-module-order-form::frontend.includes.cart_icon')
<section class="section" id="cof_orderFormGlobalSection" data-site-domain="{{ URL::to('/') }}">
	<div class="container">
		<div class="row">
			<div class="col-md-4 order-md-2 order-sm-2 order-2 mb-4" id="cof_orderFormCartSection">
				<h4 class="d-flex justify-content-between align-items-center mb-3">
				<span class="text-muted">Jouw bestelling</span>
				<span class="badge badge-secondary badge-pill" id="cof_cartTotalQuanity" data-cof-quantity="0">0</span>
				</h4>
				<ul class="list-group mb-3" id="cof_emptyCartNotice">
					<li class="list-group-item d-flex justify-content-between">
						<span>Voeg producten toe om te bestellen.</span>
					</li>
				</ul>
				<ul class="list-group mb-3" id="cof_CartProductList" style="display:none;">
					<li class="list-group-item d-flex justify-content-start lh-condensed cof_cartProductListItem" data-product-id="0" data-product-name="" data-attribute-name="" data-quantity="0" data-unit-price="0" data-total-price="0">
						<div style="padding: 7px 15px 7px 0px;">
							<img src="{{ asset('chuckbe/chuckcms-module-order-form/trash-solid.svg') }}" class="cof_deleteProductFromListButton" height="12" width="12" alt="Verwijder product" style="cursor:pointer;">
						</div>
						<div class="flex-fill cof_cartProductListDetails">
							<h6 class="my-0 cof_cartProductListItemFullName">Product name</h6>
							<small class="text-muted d-block"><span class="cof_cartProductListItemQuantity">1</span> x <span class="cof_cartProductListItemUnitPrice">€ 0,00</span></small>
							<small class="text-muted d-none cof_cartProductListItemOptions">
								<span class="cof_cartProductListItemOptionName">Optie 1</span>: <span class="cof_cartProductListItemOptionValue">Waarde</span>
							</small>
						</div>
						<span class="text-muted cof_cartProductListItemTotalPrice">€ 0,00</span>
					</li>
					<li class="list-group-item d-flex justify-content-between" id="cof_CartProductListShippingLine">
						<span>Verzending (EUR)</span>
						<strong class="cof_cartShippingPrice">€ 0,00</strong>
					</li>
					<li class="list-group-item d-flex justify-content-between" id="cof_CartProductListPriceLine">
						<span>Totaal (EUR)</span>
						<strong class="cof_cartTotalPrice">€ 0,00</strong>
					</li>
				</ul>

				<div class="card p-2 mb-3" id="cof_orderLegalCard" data-has-promo="{{ config('chuckcms-module-order-form.order.promo_check') }}">
					<div class="form-group mb-2">
						<label for="cof_orderLegalApproval" class="legal_label mb-0">
							<input type="hidden" name="legal_approval" value="0">
							<input type="checkbox" id="cof_orderLegalApproval" value="1" name="legal_approval" required> {{ config('chuckcms-module-order-form.order.legal_text') }} *
						</label>
					</div>
					@if(config('chuckcms-module-order-form.order.promo_check'))
					<div class="form-group mb-0">
						<label for="cof_orderPromoApproval" class="legal_label mb-0">
							<input type="checkbox" id="cof_orderPromoApproval" value="1" name="promo_approval"> {{ config('chuckcms-module-order-form.order.promo_text') }}
						</label>
					</div>
					@else
					<input type="hidden" name="promo_approval" value="0">
					@endif
				</div>

				<div class="card p-2 text-center" id="cof_orderBtnCard" data-has-mop="{{ config('chuckcms-module-order-form.order.has_minimum_order_price') }}" data-mop="{{ config('chuckcms-module-order-form.order.minimum_order_price') }}">
					@if(config('chuckcms-module-order-form.order.has_minimum_order_price'))
					<p class="mb-3" id="cof_minOrderP_not">Helaas, je kunt nog niet bestellen. We hanteren een minimum bestelbedrag van: € {{ number_format((float)config('chuckcms-module-order-form.order.minimum_order_price'), 2, ',', '.') }}</p>
					@endif

					<div class="error_bag hidden mb-3">
                        <div class="alert alert-danger" >
                            <strong>Opgelet!</strong><span class="error_span"></span>
                        </div>
                    </div>
					<button class="btn btn-secondary text-uppercase" id="cof_placeOrderBtnNow" disabled="disabled">Bestellen</button>
				</div>
			</div>



			<div class="col-md-8 order-md-1 order-sm-1 order-1">
				<label for="bestelling">Gegevens</label><br>
				<div class="row">
					@foreach(config('chuckcms-module-order-form.locations') as $key => $location)
					<div class="col">
						<div class="form-group">
		                    <label><input type="radio" class="cof_location_radio" data-location-key="{{ $key }}" data-first-available-date="{{ ChuckModuleOrderForm::firstAvailableDate($key) }}" data-days-of-week-disabled="{{ config('chuckcms-module-order-form.locations.'.$key.'.days_of_week_disabled') }}" data-location-type="{{ config('chuckcms-module-order-form.locations.'.$key.'.type') }}" data-delivery-cost="{{ config('chuckcms-module-order-form.locations.'.$key.'.delivery_cost') }}" data-time-required="{{ config('chuckcms-module-order-form.locations.'.$key.'.time_required') }}" data-time-min="{{ config('chuckcms-module-order-form.locations.'.$key.'.time_min') }}" data-time-max="{{ config('chuckcms-module-order-form.locations.'.$key.'.time_max') }}" data-time-default="{{ config('chuckcms-module-order-form.locations.'.$key.'.time_default') }}" name="location" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}> {{ $location['name'] }}</label><br>
				        </div>
					</div>
					@endforeach
				</div>

				<div class="row">
					<div class="col-sm-12 cof_datepicker_group">
						<div class="form-group">
							<label for="">Datum* </label><br>
		                    <input type="text" class="form-control cof_datepicker" name="order_date" id="order_date" readonly required>
				        </div>
					</div>
					<div class="col-sm-4 d-none hidden cof_datetimepicker_group">
						<div class="form-group">
							<label for="">Tijd* </label><br>
		                    <input type="text" class="form-control cof_datetimepicker datetimepicker-input" name="order_time" value="00:00" id="order_time" data-toggle="datetimepicker" data-target="#order_time" readonly required>
				        </div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-6 col-6">
						<div class="form-group">
							<label for="surname">Voornaam*</label>
		                    <input type="text" class="form-control" name="order_surname" id="surname" required> 
		                    @if ($errors->has('surname'))
		                        <span class="help-block">
		                            <strong>{{ $errors->first('surname') }}</strong>
		                        </span>
		                    @endif
		                </div> <!-- /.form-group -->
		            </div> <!-- /.col -->
		            <div class="col-sm-6 col-6">
						<div class="form-group">
							<label for="name">Achternaam*</label>
		                    <input type="text" class="form-control" name="order_name" id="name" required> 
		                    @if ($errors->has('name'))
		                        <span class="help-block">
		                            <strong>{{ $errors->first('name') }}</strong>
		                        </span>
		                    @endif
		                </div> <!-- /.form-group -->
		            </div> <!-- /.col -->
				</div> <!-- /.row -->

				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="email">Email*</label>
		                    <input type="email" class="form-control" name="order_email" id="email" required> 
		                    @if ($errors->has('email'))
		                        <span class="help-block">
		                            <strong>{{ $errors->first('email') }}</strong>
		                        </span>
		                    @endif
		                </div> <!-- /.form-group -->
		            </div> <!-- /.col -->
		            <div class="col-sm-6">
						<div class="form-group">
							<label for="tel">Telefoonnummer</label>
		                    <input type="phone" class="form-control" name="order_tel" id="tel"> 
		                    @if ($errors->has('tel'))
		                        <span class="help-block">
		                            <strong>{{ $errors->first('tel') }}</strong>
		                        </span>
		                    @endif
		                </div> <!-- /.form-group -->
		            </div> <!-- /.col -->
				</div> <!-- /.row -->

				<div class="row">
					<div class="col-sm-4 col-8">
						<div class="form-group">
							<label for="street">Straat*</label>
		                    <input type="text" class="form-control" name="order_street" id="street" required> 
		                    @if ($errors->has('street'))
		                        <span class="help-block">
		                            <strong>{{ $errors->first('street') }}</strong>
		                        </span>
		                    @endif
		                </div> <!-- /.form-group -->
		            </div> <!-- /.col -->
		            <div class="col-sm-2 col-4">
						<div class="form-group">
							<label for="housenumber">Nr*</label>
		                    <input type="text" class="form-control" name="order_housenumber" id="housenumber" required> 
		                    @if ($errors->has('housenumber'))
		                        <span class="help-block">
		                            <strong>{{ $errors->first('housenumber') }}</strong>
		                        </span>
		                    @endif
		                </div> <!-- /.form-group -->
		            </div> <!-- /.col -->
		            <div class="col-sm-2 col-4">
						<div class="form-group">
							<label for="postalcode">Postcode*</label>
		                    <input type="text" class="form-control" name="order_postalcode" id="postalcode" required> 
		                    @if ($errors->has('zipcode'))
		                        <span class="help-block">
		                            <strong>{{ $errors->first('zipcode') }}</strong>
		                        </span>
		                    @endif
		                </div> <!-- /.form-group -->
		            </div> <!-- /.col -->
		            <div class="col-sm-4 col-8">
						<div class="form-group">
							<label for="city">Gemeente*</label>
		                    <input type="text" class="form-control" name="order_city" id="city" required> 
		                    @if ($errors->has('city'))
		                        <span class="help-block">
		                            <strong>{{ $errors->first('city') }}</strong>
		                        </span>
		                    @endif
		                </div> <!-- /.form-group -->
		            </div> <!-- /.col -->
				</div> <!-- /.row -->
			</div>
		</div>
		





		@foreach(config('chuckcms-module-order-form.categories') as $catKey => $category)
		@if($category['is_displayed'])
		<div class="row equal">
			<div class="col-sm-12">
				<h4 class="mt-4">{{ $category['name'] }}</h4>
			</div>
			@foreach($products as $product)
			@if($product->json['category'] == $catKey && $product->json['is_displayed'])
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-4 chuck_ofm_product_tile" data-product-id="{{ $product->id }}" data-product-name="{{ $product->json['name'][app()->getLocale()] }}">
				<div class="thumbnail d-flex align-items-start flex-column" style="border: 1px solid #ddd;border-radius: 4px;padding:10px;height:100%;">
					@if(config('chuckcms-module-order-form.form.display_images'))
					<img src="{{ $product->json['featured_image'] ?? 'https://via.placeholder.com/500x333.jpg?text=No+Image+Found' }}" class="cof_productImage img-fluid" data-product-id="{{ $product->id }}" alt="">
					@endif
					<h3 class="mb-1 mt-1">{{ $product->json['name'][app()->getLocale()] }}</h3>
					@if(config('chuckcms-module-order-form.form.display_description'))
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
									<button class="btn btn-outline-secondary cof_additionProductBtn" data-product-id="{{ $product->id }}">+</button>
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
									@if(array_key_exists('options', $product->json) && count($product->json['options']) > 0)
									<button class="btn btn-outline-primary cof_btnAddProductAttributeOptionsToCart" data-product-id="{{ $product->id }}" data-product-options="{{ json_encode($product->json['options']) }}">Toevoegen</button>
									@else
									<button class="btn btn-outline-primary cof_btnAddProductAttributeToCart" data-product-id="{{ $product->id }}">Toevoegen</button>
									@endif
								</div>
							</div>
						</div>
						@else
						<div class="col-sm-6 col-6">
							<div class="input-group mb-3">
								@if(array_key_exists('options', $product->json) && count($product->json['options']) > 0)
								<button class="btn btn-outline-primary btn-block cof_btnAddProductOptionsToCart" data-product-id="{{ $product->id }}" data-product-options="{{ json_encode($product->json['options']) }}">Toevoegen</button>
								@else
								<button class="btn btn-outline-primary btn-block cof_btnAddProductToCart" data-product-id="{{ $product->id }}">Toevoegen</button>
								@endif
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
			@endif
			@endforeach
		</div>
		@endif
		@endforeach
	</div>
</section>
<div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="optionsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="" id="options-form">
            <div class="modal-header">
                <h5 class="modal-title font-cera-bold" id="optionsModalLabel">Selecteer de opties voor: <span class="options_product_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="optionsModalBody">
                
                <div class="row options_modal_row">
                    
                    <div class="col-sm-12 options_modal_item_radio">
                    	<label for="" class="options_item_name">Radio</label>
                        <div class="form-group cof_options_radio_item_input_group mb-2">
                            <div class="form-check cof_options_radio_item_input">
								<label class="form-check-label" for="exampleRadios1">
								<input class="form-check-input" type="radio" name="cof_options_radio" id="exampleRadios1" value="option1">
								<span> Default radio</span>
								</label>
							</div>
                        </div>
                    </div>

                    <div class="col-sm-12 options_modal_item_select">
                        <div class="form-group">
                            <label for="cofOptionsSelect" class="options_item_name">Select</label>
                            <select name="cof_options_select" class="custom-select cof_options_select_item_input" required>
                                <option value="default" class="cof_options_option_input">Default</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-block" id="addProductWithOptionsToCartButton">Toevoegen</button>
            </div>
        </form>
    </div>
  </div>
</div>