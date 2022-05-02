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
	<ul class="list-group mb-1" id="cof_CartProductList" style="display:none;">
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
				<small class="text-muted d-none cof_cartProductListItemExtras">
					<span class="cof_cartProductListItemOptionName">Optie 1</span> <span class="cof_cartProductListItemOptionValue">Waarde</span>
				</small>
				<small class="text-muted d-none cof_cartProductListItemSubproducts">
					<span class="cof_cartProductListItemSubproductGroupName">Group 1</span>
					<span class="cof_cartProductListItemSubproductGroupItems">
						<ul class="pl-2 ps-2">
							<li>Product 1</li>
						</ul>
					</span>
				</small>
			</div>
			<span class="text-muted cof_cartProductListItemTotalPrice">€ 0,00</span>
		</li>
		<li class="list-group-item d-flex justify-content-between" id="cof_CartProductListShippingLine">
			<span>Verzending (EUR)</span>
			<strong class="cof_cartShippingPrice">€ 0,00</strong>
		</li>
		<li class="list-group-item d-flex justify-content-between d-none" id="cof_CartProductListDiscountLine">
			<span>Korting (EUR)</span>
			<strong class="cof_cartDiscountPrice">-€ 0,00</strong>
		</li>
		<li class="list-group-item d-flex justify-content-between" id="cof_CartProductListPriceLine">
			<span>Totaal (EUR)</span>
			<strong class="cof_cartTotalPrice">€ 0,00</strong>
		</li>
	</ul>
	
	<div class="d-block w-100 d-none" id="cof_orderDiscountCodeOpenBtnWrapper">
		<a href="#" class="d-block pb-2 text-right" id="cof_orderDiscountCodeOpenBtn"><small>Heb je een kortingscode?</small></a>
	</div>

	<div class="card p-2 mb-3 d-none" id="cof_orderDiscountCard">
		<div class="form-group mb-0">
			<div class="input-group">
				<input type="text" class="form-control rounded-0 rounded-left" id="cof_orderDiscountCode" name="discount_code" placeholder="Kortingscode">
				<div class="input-append">
					<button class="btn btn-secondary rounded-0 rounded-right" id="cof_orderDiscountCheckBtn">Check</button>
				</div>
			</div>
		</div>
		<div class="coupon_error_bag d-none">
            <div class="alert alert-danger mt-2 mb-0">
                <strong><span class="coupon_error_span">Error hier</span></strong>
            </div>
        </div>
	</div>

	<div class="card p-2 mb-3" id="cof_orderLegalCard" data-has-promo="{{ $settings['order']['promo_check'] }}">
		<div class="form-group mb-2">
			<label for="cof_orderLegalApproval" class="legal_label mb-0">
				<input type="hidden" name="legal_approval" value="0">
				<input type="checkbox" id="cof_orderLegalApproval" value="1" name="legal_approval" required> {{ $settings['order']['legal_text'] }} *
			</label>
		</div>
		@if($settings['order']['promo_check'])
		<div class="form-group mb-0">
			<label for="cof_orderPromoApproval" class="legal_label mb-0">
				<input type="checkbox" id="cof_orderPromoApproval" value="1" name="promo_approval"> {{ $settings['order']['promo_text'] }}
			</label>
		</div>
		@else
		<input type="hidden" name="promo_approval" value="0">
		@endif
	</div>

	<div class="card p-2 text-center" id="cof_orderBtnCard" data-has-mop="{{ $settings['order']['has_minimum_order_price'] }}" data-mop="{{ $settings['order']['minimum_order_price'] }}">
		@if($settings['order']['has_minimum_order_price'])
		<p class="mb-3" id="cof_minOrderP_not">Helaas, je kunt nog niet bestellen. We hanteren een minimum bestelbedrag van: € {{ number_format((float)$settings['order']['minimum_order_price'], 2, ',', '.') }}</p>
		@endif

		<div class="error_bag hidden mb-3">
            <div class="alert alert-danger" >
                <strong>Opgelet!</strong><span class="error_span"></span>
            </div>
        </div>
		<button class="btn btn-secondary text-uppercase" id="cof_placeOrderBtnNow" disabled="disabled">Bestellen</button>
	</div>
</div>