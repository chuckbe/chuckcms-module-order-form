@php
$settings = ChuckSite::module('chuckcms-module-order-form')->settings;
@endphp

@if($settings['cart']['use_ui'] == true)
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endif

@if(config('chuckcms-module-order-form.datepicker.js.use') == true)
<script src="{{ config('chuckcms-module-order-form.datepicker.js.asset') ? asset(config('chuckcms-module-order-form.datepicker.js.link')) : config('chuckcms-module-order-form.datepicker.js.link') }}"></script>
<script src="{{ config('chuckcms-module-order-form.datepicker.js.locale_asset') ? asset(config('chuckcms-module-order-form.datepicker.js.locale_link')) : config('chuckcms-module-order-form.datepicker.js.locale_link') }}"></script>
@endif

@if(config('chuckcms-module-order-form.moment.js.use') == true)
<script src="{{ config('chuckcms-module-order-form.moment.js.asset') ? asset(config('chuckcms-module-order-form.moment.js.link')) : config('chuckcms-module-order-form.moment.js.link') }}"></script>
@endif

@if(config('chuckcms-module-order-form.datetimepicker.js.use') == true)
<script src="{{ config('chuckcms-module-order-form.datetimepicker.js.asset') ? asset(config('chuckcms-module-order-form.datetimepicker.js.link')) : config('chuckcms-module-order-form.datetimepicker.js.link') }}"></script>
@endif


<script type="text/javascript">
	var cof_dp_startdate = {};
</script>

@foreach(ChuckRepeater::for(config('chuckcms-module-order-form.locations.slug'))->sortBy('json.order') as $location)
<script type="text/javascript">
	cof_dp_startdate['{{ $location->id }}'] = '+{{ ChuckModuleOrderForm::firstAvailableDateInDaysFromNow($location->id) }}d';
</script>
@endforeach
<script type="text/javascript">
var order_url = "{{ route('cof.place_order') }}";
var is_address_eligible_url = "{{ route('cof.is_address_eligible') }}";
var a_token = "{{ Session::token() }}";
if (!$.fn.bootstrapDP && $.fn.datepicker && $.fn.datepicker.noConflict) {
	var datepicker = $.fn.datepicker.noConflict();
	$.fn.bootstrapDP = datepicker;
}
</script>


<script type="text/javascript">
$(document).ready(function() {
	var OGlocationKey = $('.cof_location_radio:checked').attr('data-location-key');
	var OGlocationDate = $('.cof_location_radio:checked').attr('data-first-available-date');
	var OGlocationDatesDisabled = $('.cof_location_radio:checked').attr('data-dates-disabled') !== undefined ? $('.cof_location_radio:checked').attr('data-dates-disabled').split(',') : [];

	$('.cof_datepicker').val(OGlocationDate);

	$('.cof_datepicker').bootstrapDP({
	    format: 'dd/mm/yyyy',
	    startDate: cof_dp_startdate[OGlocationKey],
	    weekStart: 1,
	    language: "{{ config('chuckcms-module-order-form.datepicker.js.locale') }}",
	    daysOfWeekDisabled: $('.cof_location_radio:checked').attr('data-days-of-week-disabled'),
	    datesDisabled: OGlocationDatesDisabled
	});

	if($('.cof_location_radio:checked').attr('data-location-type') == 'delivery') {
		location_shipping_price = $('.cof_location_radio:checked').attr('data-delivery-cost');
		$('.cof_cartShippingPrice').text('€ '+parseFloat(location_shipping_price).toFixed(2).replace('.', ','));
	}
	calculateProductQty();

	if($('.cof_location_radio:checked').attr('data-time-required') == true) {
		datetime_default = $('.cof_location_radio:checked').attr('data-time-default');
		datetime_min = $('.cof_location_radio:checked').attr('data-time-min');
		datetime_max = $('.cof_location_radio:checked').attr('data-time-max');
		$('.cof_datetimepicker').val(datetime_default);
		$('.cof_datetimepicker').datetimepicker({
            format: 'HH:mm',
            date: moment(datetime_default, 'HH:mm'),
            stepping: 15,
            disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: datetime_min })], [moment({ h: datetime_max }), moment({ h: 24 })]],
            ignoreReadonly: true,
            locale: "{{ config('chuckcms-module-order-form.datetimepicker.js.locale') }}"
        });

        $('.cof_datepicker_group').removeClass('col-sm-12').addClass('col-sm-8');
        $('.cof_datetimepicker_group').removeClass('d-none hidden');
	}
			

	$('body').on('change', '.cof_location_radio', function (event) {
		var locationKey = $('.cof_location_radio:checked').attr('data-location-key');
		var locationType = $('.cof_location_radio:checked').attr('data-location-type');
		var locationDate = $('.cof_location_radio:checked').attr('data-first-available-date');
		var locationDaysOfWeekDisabled = $('.cof_location_radio:checked').attr('data-days-of-week-disabled');
		var locationDatesDisabled = ($('.cof_location_radio:checked').attr('data-dates-disabled') !== undefined ? $('.cof_location_radio:checked').attr('data-dates-disabled').split(',') : []);
		var deliveryTimeRequired = $('.cof_location_radio:checked').attr('data-time-required');

		$('.cof_datepicker').bootstrapDP('destroy');
		$('.cof_datepicker').val(locationDate);
		$('.cof_datepicker').bootstrapDP({
		    format: 'dd/mm/yyyy',
		    startDate: cof_dp_startdate[locationKey],
		    weekStart: 1,
		    language: "{{ config('chuckcms-module-order-form.datepicker.js.locale') }}",
		    daysOfWeekDisabled: locationDaysOfWeekDisabled,
		    datesDisabled: locationDatesDisabled
		});

		$('.cof_datepicker_group').removeClass('col-sm-8').addClass('col-sm-12');
        $('.cof_datetimepicker_group').addClass('d-none hidden');

		if(deliveryTimeRequired == true) {
			$('.cof_datetimepicker').datetimepicker('destroy');
			datetime_default = $('.cof_location_radio:checked').attr('data-time-default');
			datetime_min = $('.cof_location_radio:checked').attr('data-time-min');
			datetime_max = $('.cof_location_radio:checked').attr('data-time-max');
			
			$('.cof_datetimepicker').val(datetime_default);
			
			$('.cof_datetimepicker').datetimepicker({
                format: 'HH:mm',
                date: moment(datetime_default, 'HH:mm'),
                stepping: 15,
                disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: datetime_min })], [moment({ h: datetime_max }), moment({ h: 24 })]],
                ignoreReadonly: true,
                locale: "{{ config('chuckcms-module-order-form.datetimepicker.js.locale') }}"
            });

            $('.cof_datepicker_group').removeClass('col-sm-12').addClass('col-sm-8');
            $('.cof_datetimepicker_group').removeClass('d-none hidden');

		}

		calculateTotalPrice();
		calculateProductQty();
	});

	$('body').on('click', '.cof_additionProductBtn', function (event) {
		event.preventDefault();
		product_id = $(this).attr('data-product-id');
		max_q = $(this).attr('data-max-q');
		newValue = parseInt($('.cof_productQuantityInput[data-product-id='+product_id+']').val()) + 1;
		if( (parseInt(max_q) == -1) || (newValue <= parseInt(max_q)) ) {
			$('.cof_productQuantityInput[data-product-id='+product_id+']').val(newValue);
		}
	});

	$('body').on('click', '.cof_subtractionProductBtn', function (event) {
		event.preventDefault();
		product_id = $(this).attr('data-product-id');
		newValue = parseInt($('.cof_productQuantityInput[data-product-id='+product_id+']').val()) - 1;
		if(newValue >= 1) {
			$('.cof_productQuantityInput[data-product-id='+product_id+']').val(newValue);
		}
	});
	
	$('body').on('change', '.cof_attributeSelectInput', function (event) {
		//event.preventDefault();
		product_id = $(this).attr('data-product-id');
		site_domain = $('#cof_orderFormGlobalSection').attr('data-site-domain');
		attribute_img = $(this).children("option:selected").attr('data-attribute-img');

		$('.cof_attributeSelectInput[data-product-id='+product_id+']').removeClass('is-invalid');

		if(attribute_img !== 'false') {
			$('.cof_productImage[data-product-id='+product_id+']').attr('src', attribute_img);
		}

		attribute_price = $(this).children("option:selected").attr('data-attribute-price');
		og_unit_price = $('.cof_productItemUnitPrice[data-product-id='+product_id+']').attr('data-product-price');
		has_discount = $('.cof_productItemUnitPrice[data-product-id='+product_id+']').attr('data-has-discount') == 'true' ? true : false;

		if(has_discount == true && attribute_price !== '' && attribute_price !== null) {
			if(parseFloat(attribute_price) < parseFloat(og_unit_price)) {
				$('.cof_productItemDiscountPrice[data-product-id='+product_id+']').show();
				$('.cof_productItemDiscountPrice[data-product-id='+product_id+']').text('€ '+parseFloat(attribute_price).toFixed(2).replace('.', ','));
				$('.cof_productItemUnitPrice[data-product-id='+product_id+']').text('€ '+parseFloat(og_unit_price).toFixed(2).replace('.', ','));
				$('.cof_productItemUnitPrice[data-product-id='+product_id+']').css('text-decoration', 'line-through');
			} else {
				$('.cof_productItemDiscountPrice[data-product-id='+product_id+']').hide();
				$('.cof_productItemUnitPrice[data-product-id='+product_id+']').text('€ '+parseFloat(attribute_price).toFixed(2).replace('.', ','));
				$('.cof_productItemUnitPrice[data-product-id='+product_id+']').css('text-decoration', 'none');
			}
			//attribute_price is the new current price
			$('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price', attribute_price);
		} else if(has_discount == true && (attribute_price == '' || attribute_price == null)) {
			$('.cof_productItemUnitPrice[data-product-id='+product_id+']').text('€ '+parseFloat(og_unit_price).toFixed(2).replace('.', ','));
			$('.cof_productItemUnitPrice[data-product-id='+product_id+']').css('text-decoration', 'line-through');	
			$('.cof_productItemDiscountPrice[data-product-id='+product_id+']').show();
			discount_price = $('.cof_productItemDiscountPrice[data-product-id='+product_id+']').attr('data-discount-price');
			$('.cof_productItemDiscountPrice[data-product-id='+product_id+']').text('€ '+parseFloat(discount_price).toFixed(2).replace('.', ','));
			//discount_price is the new current price
			$('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price', discount_price);
		} else if(has_discount == false && attribute_price !== '' && attribute_price !== null) {
			$('.cof_productItemUnitPrice[data-product-id='+product_id+']').text('€ '+parseFloat(attribute_price).toFixed(2).replace('.', ','));
			$('.cof_productItemUnitPrice[data-product-id='+product_id+']').css('text-decoration', 'none');
			//attribute_price is the new current price
			$('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price', attribute_price);
		} else if(has_discount == false && (attribute_price == '' || attribute_price == null)) {
			$('.cof_productItemUnitPrice[data-product-id='+product_id+']').text('€ '+parseFloat(og_unit_price).toFixed(2).replace('.', ','));
			$('.cof_productItemUnitPrice[data-product-id='+product_id+']').css('text-decoration', 'none');
			//og_unit_price is the new current price
			$('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price', og_unit_price);
		}
	});






	$('body').on('click', '.cof_btnAddProductToCart', function (event) {
		event.preventDefault();

		product_id = $(this).attr('data-product-id');
		current_price = $('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price');
		quantity = $('.cof_productQuantityInput[data-product-id='+product_id+']').val();
		product_name = $('.chuck_ofm_product_tile[data-product-id='+product_id+']').attr('data-product-name');
		total_price = parseFloat(current_price) * parseInt(quantity);

		cart_count = parseInt($('#cof_cartTotalQuanity').attr('data-cof-quantity'));
		if(cart_count == 0) {
			$('#cof_emptyCartNotice').hide();
			$('#cof_CartProductList').show();

			selector = '.cof_cartProductListItem:first';
			attribute_name = '';
			product_options_json = '';
			product_extras_json = '';
			updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);
			
		} else {
			if($('.cof_cartProductListItem[data-product-id='+product_id+']').length > 0) {

				selector = '.cof_cartProductListItem[data-product-id='+product_id+']';
				updateProductListItemQuantity(selector, quantity, total_price);

				
			} else {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				selector = '.cof_cartProductListItem:last';
				attribute_name = '';
				product_options_json = '';
				product_extras_json = '';
				updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));

				$('#cof_CartProductListShippingLine').insertAfter($('.cof_cartProductListItem:last'));
			}
		}

		$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count + parseInt(quantity));
		$('#cof_cartTotalQuanity').text(cart_count + parseInt(quantity));
		$('.cof_cartTotalQuanity').text(cart_count + parseInt(quantity));

		//reset original product tile qty input to 1
		$('.cof_productQuantityInput[data-product-id='+product_id+']').val(1);

		var cart = $('.cof_cartIconLeftCorner');
        var imgtodrag = $('.cof_productImage[data-product-id='+product_id+']').eq(0);
        if (imgtodrag.length) {
        	qty = cart_count + parseInt(quantity);
            animateImgToCart(cart, imgtodrag, qty);
        }

		calculateTotalPrice();
	});



	$('body').on('click', '.cof_btnAddProductAttributeToCart', function (event) {
		event.preventDefault();

		product_id = $(this).attr('data-product-id');

		$('.cof_attributeSelectInput[data-product-id='+product_id+']').removeClass('is-invalid');

		if($('.cof_attributeSelectInput[data-product-id='+product_id+']').children("option:selected").attr('data-option-is') == 'false') {
			$('.cof_attributeSelectInput[data-product-id='+product_id+']').addClass('is-invalid');
			return;
		}

		current_price = $('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price');
		quantity = $('.cof_productQuantityInput[data-product-id='+product_id+']').val();

		product_name = $('.chuck_ofm_product_tile[data-product-id='+product_id+']').attr('data-product-name');

		attribute_name = $('.cof_attributeSelectInput[data-product-id='+product_id+']').children("option:selected").attr('data-attribute-name');
		total_price = parseFloat(current_price) * parseInt(quantity);

		cart_count = parseInt($('#cof_cartTotalQuanity').attr('data-cof-quantity'));

		if(cart_count == 0) {
			$('#cof_emptyCartNotice').hide();
			$('#cof_CartProductList').show();

			selector = '.cof_cartProductListItem:first';
			product_options_json = '';
			product_extras_json = '';
			updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);

		} else {
			
			if($('.cof_cartProductListItem[data-product-id='+product_id+']').length == 1 && attribute_name == $('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-attribute-name')) {

				selector = '.cof_cartProductListItem[data-product-id='+product_id+']';
				updateProductListItemQuantity(selector, quantity, total_price);

			} else if($('.cof_cartProductListItem[data-product-id='+product_id+']').length == 0) {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				selector = '.cof_cartProductListItem:last';
				product_options_json = '';
				product_extras_json = '';
				updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));

				$('#cof_CartProductListShippingLine').insertAfter($('.cof_cartProductListItem:last'));
			} else if($('.cof_cartProductListItem[data-product-id='+product_id+']').length == 1 && attribute_name !== $('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-attribute-name')) {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				selector = '.cof_cartProductListItem:last';
				product_options_json = '';
				product_extras_json = '';
				updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));

				$('#cof_CartProductListShippingLine').insertAfter($('.cof_cartProductListItem:last'));
			} else if($('.cof_cartProductListItem[data-product-id='+product_id+']').length > 1 && $('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').length > 0) {

				selector = '.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]';
				updateProductListItemQuantity(selector, quantity, total_price);

			} else if($('.cof_cartProductListItem[data-product-id='+product_id+']').length > 1 && $('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').length == 0) {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				selector = '.cof_cartProductListItem:last';
				product_options_json = '';
				product_extras_json = '';
				updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));

				$('#cof_CartProductListShippingLine').insertAfter($('.cof_cartProductListItem:last'));
			}

		}

		$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count + parseInt(quantity));
		$('#cof_cartTotalQuanity').text(cart_count + parseInt(quantity));

		//reset original product tile qty input to 1
		$('.cof_productQuantityInput[data-product-id='+product_id+']').val(1);

        var cart = $('.cof_cartIconLeftCorner');
        var imgtodrag = $('.cof_productImage[data-product-id='+product_id+']').eq(0);
        if (imgtodrag.length) {
            qty = cart_count + parseInt(quantity);
            animateImgToCart(cart, imgtodrag, qty);
        }
    

		calculateTotalPrice();
	});

	$('body').on('click', '#addProductWithOptionsToCartButton', function (event) {
		event.preventDefault();

		product_id = $(this).attr('data-product-id');

		current_price = $('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price');
		quantity = $('.cof_productQuantityInput[data-product-id='+product_id+']').val();

		product_name = $('.chuck_ofm_product_tile[data-product-id='+product_id+']').attr('data-product-name');

		if ($('.cof_attributeSelectInput[data-product-id='+product_id+']').length) {
			attribute_name = $('.cof_attributeSelectInput[data-product-id='+product_id+']').children("option:selected").attr('data-attribute-name');
		} else {
			attribute_name = '';
		}

		total_price = parseFloat(current_price) * parseInt(quantity);

		product_options = getSelectedProductOptions();
		product_options_json = JSON.stringify(product_options);

		product_extras = getSelectedProductExtras();
		product_extras_json = JSON.stringify(product_extras);

		cart_count = parseInt($('#cof_cartTotalQuanity').attr('data-cof-quantity'));

		if(cart_count == 0) {
			$('#cof_emptyCartNotice').hide();
			$('#cof_CartProductList').show();

			selector = '.cof_cartProductListItem:first';
			updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);

		} else {

// if empty cart: add product
// else if product_id is in cart? > 	if product_id 

// 									if attribute check and options check = update quantity 
// 									else (attribute is equal to nor product options so we cant update) = add product
// else if product_id isnot in cart? >	= add product



			if($('.cof_cartProductListItem[data-product-id='+product_id+']').length > 0) {

				changed = 0;

				if(product_extras_json == '[]') {
					product_extras_json = '';
				}

				if(product_options_json == '[]') {
					product_options_json = '';
				}

				$('.cof_cartProductListItem[data-product-id='+product_id+']').each(function() {
					console.log('test me out toniggggght)');
					console.log('gustoo :', ""+product_extras_json+"", 'gustoo 2 :', $(this).attr('data-product-extras'), 'bellooo :', ""+product_options_json+"", 'belloo 2 :', $(this).attr('data-product-options'));
					if(attribute_name == $(this).attr('data-attribute-name') && ""+product_options_json+"" == $(this).attr('data-product-options') && ""+product_extras_json+"" == $(this).attr('data-product-extras')) {

						random = Math.random().toString(36).substr(2, 5);
						$(this).attr('data-unique-el', random);

						if(product_extras_json.length > 0 && product_extras_json !== undefined && product_extras_json !== '[]') {
							prodex_json = ""+product_extras_json+"";
							extrakes = JSON.parse(product_extras_json);
							extras_price = 0;
							for (var i = 0; i < extrakes.length; i++) {
								if(!$.isEmptyObject(extrakes[i]) ) {
									extras_price = extras_price + parseFloat(extrakes[i]['value']);
								}
								
							};
							console.log('extraaaaaaaassss price :', extras_price);
						} else {
							prodex_json = '';
							extras_price = 0;
						}

						current_price = current_price + extras_price;
						total_price = total_price + (quantity * extras_price);
						
						selector = ".cof_cartProductListItem[data-product-id="+product_id+"][data-unique-el="+random+"]";
						updateProductListItemQuantity(selector, quantity, total_price);
						changed = 1;
						return false
					}
				});

				if(changed == 0) {
					
					$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

					selector = '.cof_cartProductListItem:last';
					updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);

					$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));

					$('#cof_CartProductListShippingLine').insertAfter($('.cof_cartProductListItem:last'));
					
				}
				

				
			} else {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				selector = '.cof_cartProductListItem:last';
				updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price);

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));

				$('#cof_CartProductListShippingLine').insertAfter($('.cof_cartProductListItem:last'));
			}			

		}

		$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count + parseInt(quantity));
		$('#cof_cartTotalQuanity').text(cart_count + parseInt(quantity));

		//reset original product tile qty input to 1
		$('.cof_productQuantityInput[data-product-id='+product_id+']').val(1);

		var cart = $('.cof_cartIconLeftCorner');
        
        setTimeout(function () {
            cart.effect("shake", {
                times: 2
            }, 200);
            $('.cof_cartTotalQuanity').text(cart_count + parseInt(quantity));
        }, 1000);

		calculateTotalPrice();

		$('#optionsModal').modal('hide');
	});


	$('body').on('click', '.cof_btnAddProductOptionsToCart', function (event) {
		event.preventDefault();

		product_id = $(this).attr('data-product-id');
		product_name = $('.chuck_ofm_product_tile[data-product-id='+product_id+']').attr('data-product-name');
		current_price = $('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price');
		quantity = $('.cof_productQuantityInput[data-product-id='+product_id+']').val();
		total_price = parseFloat(current_price) * parseInt(quantity);

		product_options = JSON.parse($(this).attr('data-product-options'));
		if ( $(this).attr('data-product-extras') !== undefined ) {
			product_extras = JSON.parse($(this).attr('data-product-extras'));
		} else {
			product_extras = [];
		}

		resetOptionsModal();
		setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_options, product_extras);

		$('#optionsModal').modal();
	});

	$('body').on('click', '.cof_btnAddProductAttributeOptionsToCart', function (event) {
		event.preventDefault();

		product_id = $(this).attr('data-product-id');

		$('.cof_attributeSelectInput[data-product-id='+product_id+']').removeClass('is-invalid');

		if($('.cof_attributeSelectInput[data-product-id='+product_id+']').children("option:selected").attr('data-option-is') == 'false') {
			$('.cof_attributeSelectInput[data-product-id='+product_id+']').addClass('is-invalid');
			return;
		}

		current_price = $('.cof_productItemPriceDisplay[data-product-id='+product_id+']').attr('data-current-price');
		quantity = $('.cof_productQuantityInput[data-product-id='+product_id+']').val();

		product_name = $('.chuck_ofm_product_tile[data-product-id='+product_id+']').attr('data-product-name');

		attribute_name = $('.cof_attributeSelectInput[data-product-id='+product_id+']').children("option:selected").attr('data-attribute-name');
		total_price = parseFloat(current_price) * parseInt(quantity);

		product_options = JSON.parse($(this).attr('data-product-options'));
		if ( $(this).attr('data-product-extras') !== undefined ) {
			product_extras = JSON.parse($(this).attr('data-product-extras'));
		} else {
			product_extras = [];
		}

		resetOptionsModal();
		setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_options, product_extras);

		$('#optionsModal').modal();
	});

	

	
	$('body').on('click', '.cof_deleteProductFromListButton', function (event) {
		event.preventDefault();
		
		product_id = $(this).closest('.cof_cartProductListItem').attr('data-product-id');
		attribute_name = $(this).closest('.cof_cartProductListItem').attr('data-attribute-name');
		attribute_name = $(this).closest('.cof_cartProductListItem').attr('data-attribute-name');

		if($('.cof_cartProductListItem').length > 1) {
			//more than one item in the list so remove the correct element
			product_quantity = $(this).closest('.cof_cartProductListItem').attr('data-quantity');
			$(this).closest('.cof_cartProductListItem').remove();

			//subtract product qty from the cart qty and update Cart Count
			cart_count = $('#cof_cartTotalQuanity').attr('data-cof-quantity');
			new_count = cart_count - parseInt(product_quantity);
			updateCartCount(new_count);
			
		} else if( $('.cof_cartProductListItem').length == 1) {
			//only one item in the cart so hide productlist and show the 'empty card' notice
			$('#cof_CartProductList').hide();
			$('#cof_emptyCartNotice').show();
			
			//reset the one item in the cart so it's blank
			resetFirstProduct();
			
			//update the cart count to zero
			updateCartCount(0);
			
		}

		calculateTotalPrice();
	});

	$('body').on('click', '#cof_placeOrderBtnNow', function (event) {
		$('.error_bag:first').addClass('hidden');
		$(this).html('Even geduld ...');
        $(this).prop('disabled', true);

		if( !validateForm() ) {
			$('.error_span:first').html(' Niet alle verplichte velden zijn ingevuld...');
			$('.error_bag:first').removeClass('hidden');

			$(this).html('Bestellen');
    		$(this).prop('disabled', false);
			return false;
		}

		if( $('input[type=checkbox][name=legal_approval]:checked').length == 0 ) {
			$('.error_span:first').html(' Uw goedkeuring is vereist om deze bestelling te kunnen plaatsen.');
			$('.error_bag:first').removeClass('hidden');

			$(this).html('Bestellen');
    		$(this).prop('disabled', false);
			return false;
		}
		
        var products = getProducts();
        var price = getTotalPrice();
        var shipping = calculateShippingPrice();
        
		if ( price == 0.00 ) {
			$('.error_span:first').html(' U heeft geen producten geselecteerd...');
			$('.error_bag:first').removeClass('hidden');
			return false;
		}

		if( $('.cof_location_radio:checked').attr('data-location-type') == 'takeout' ) {
			placeOrder(products,price,shipping);
		} else {
			isAddressEligible().done(function(data){
	            if (data.status == "success"){
	                placeOrder(products,price,shipping);
	            } else {
	                $('.error_span:first').html(' Uw adres valt helaas niet binnen het leveringsgebied...');
					$('.error_bag:first').removeClass('hidden');

					$('#cof_placeOrderBtnNow').html('Bestellen');
		    		$('#cof_placeOrderBtnNow').prop('disabled', false);
					return false;
	            }
	        
			});
		}

		return false;
	});

	function placeOrder(products, price, shipping) {
		$.ajax({
            method: 'POST',
            url: order_url,
            data: { 
            	location: $('input[name=location]:checked').val(), 
            	order_date: $('input[name=order_date]').val(), 
            	order_time: $('input[name=order_time]').val(), 
            	surname: $('input[name=order_surname]').val(), 
            	name: $('input[name=order_name]').val(),
            	email: $('input[name=order_email]').val(),
            	tel: $('input[name=order_tel]').val(),
            	street: $('input[name=order_street]').val(),
            	housenumber: $('input[name=order_housenumber]').val(),
            	postalcode: $('input[name=order_postalcode]').val(),
            	city: $('input[name=order_city]').val(),
            	remarks: $('textarea[name=order_remarks]').val(),
            	order: products,
            	total: price,
            	shipping: shipping,
            	legal_approval: $('input[name=legal_approval]').val(),
            	promo_approval: $('input[name=promo_approval]').val(),
            	_token: a_token
            }
        })
        .done(function(data) {
            if (data.status == "success"){
                window.location.href = data.url;
            }
            else{
                $('#cof_placeOrderBtnNow').html('Bestellen');
        		$('#cof_placeOrderBtnNow').prop('disabled', false);

        		$('.error_span:first').html(' Er is iets misgelopen, probeer het later nog eens!');
				$('.error_bag:first').removeClass('hidden');
            }
        });
	}

	function validateForm() {
	    var valid = true;
	    $('.legal_label').first().css('color', '#555');

	    $('input[required]').each(function () {
	        if ($(this).val() === '') {
	        	$(this).addClass('is-invalid');
	            valid = false;
	        } else {
	        	$(this).removeClass('is-invalid');
	        }
	    });
	    if($('input[name=legal_approval]:checked').length == 0) {
	    	$('.legal_label').first().css('color', 'red');
	    }
	    return valid
	}

	function getProducts() {
    	var products = [];
		$('.cof_cartProductListItem').each(function() {
   			var elem = $(this);

   			products.push({ 
   					product_id: elem.attr('data-product-id'),
					attributes: elem.attr('data-attribute-name') == '' ? false : elem.attr('data-attribute-name'), 
					options: elem.attr('data-product-options') == '' ? false : elem.attr('data-product-options'),
					extras: elem.attr('data-product-extras') == '' ? false : elem.attr('data-product-extras'), 
					name: elem.attr('data-product-name'), 
					price: Number(elem.attr('data-unit-price')),
					qty: Number(elem.attr('data-quantity')),
					totprice: Number(elem.attr('data-total-price'))
				})


	   //    	// If value has changed and is not empty...
	   //    	if (elem.attr('data-attribute-name') == '' && elem.attr('data-product-options') == '') {
	   //    		var key = elem.attr('data-product-id');
				// products[key] = { 
				// 	attributes: false, 
				// 	options: false, 
				// 	name: elem.attr('data-product-name'), 
				// 	price: Number(elem.attr('data-unit-price')),
				// 	qty: Number(elem.attr('data-quantity')),
				// 	totprice: Number(elem.attr('data-total-price'))
				// };
	   //  	}

	   //  	// If value has changed and is not empty...
	   //    	if (elem.attr('data-attribute-name') == '' && elem.attr('data-product-options') !== '') {
	   //    		var key = elem.attr('data-product-id');
				// products[key] = { 
				// 	attributes: false, 
				// 	options: false, 
				// 	name: elem.attr('data-product-name'), 
				// 	price: Number(elem.attr('data-unit-price')),
				// 	qty: Number(elem.attr('data-quantity')),
				// 	totprice: Number(elem.attr('data-total-price'))
				// };
	   //  	}

	   //  	if (elem.attr('data-is-attribute') !== '') {
	   //    		var key = elem.attr('data-product-id');
	   //    		if(!products.hasOwnProperty(key)){
	   //    			products[key] = {attributes: true} 
	   //    		}
				// var keyAttr = convertToSlug(elem.attr('data-attribute-name'));
				// products[key][keyAttr] = {
				// 	name: elem.attr('data-product-name') + ' - ' + elem.attr('data-attribute-name'), 
				// 	price: Number(elem.attr('data-unit-price')),
				// 	qty: Number(elem.attr('data-quantity')),
				// 	totprice: Number(elem.attr('data-total-price'))
				// };
	   //  	}
		});
		return products;
    }

    function getSelectedProductOptions()
    {
    	product_options = [];
    	if(!$('#optionsModalBody').hasClass('d-none')) {
    		$('.options_modal_row').each(function() {
	    		
	    		option_name = $(this).attr('data-product-option-name');
	    		
	    		type = $(this).attr('data-product-option-type');
	    		input_name = $(this).attr('data-product-option-input-name');
	    		if(type == 'radio') {
	    			option_value = $(this).find('input[name='+input_name+']:checked').first().val();
	    		} else if(type == 'select') {
	    			option_value = $(this).find('select[name='+input_name+']').first().val();
	    		}

	    		option_object = {name: option_name, value: option_value};
	    		product_options.push(option_object)
	    	});
    	}

    	return product_options;
    }

    function getSelectedProductExtras()
    {
    	product_options = [];
    	if(!$('#extrasModalBody').hasClass('d-none')) {
    		$('.extras_modal_row').each(function() {
	    		
	    		option_name = $(this).find('input:checked').first().val();
	    		option_value = $(this).find('input:checked').first().attr('data-product-extra-item-price');

	    		option_object = {name: option_name, value: option_value};
	    		product_options.push(option_object)
	    	});
    	}

    	return product_options;
    }

    function convertToSlug(Text)
	{
		return Text
		    .toLowerCase()
		    .replace(/[^\w ]+/g,'')
		    .replace(/ +/g,'-')
		    .replace('-','')
		    ;
	}

	function resetFirstProduct()
	{
		$('.cof_cartProductListItem:first').attr('data-product-id', 0);
		$('.cof_cartProductListItem:first').attr('data-quantity', 0);
		$('.cof_cartProductListItem:first').attr('data-product-name', '');
		$('.cof_cartProductListItem:first').attr('data-attribute-name', '');
		$('.cof_cartProductListItem:first').attr('data-product-options', '');
		$('.cof_cartProductListItem:first').attr('data-unit-price', 0);
		$('.cof_cartProductListItem:first').attr('data-total-price', 0);
		$('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:not(:first)').remove();
		$('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:first').removeClass('d-block').addClass('d-none');
	}

	function updateProductListItemAttributes(selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price)
	{
		$(''+selector+'').attr('data-product-id', product_id);
		$(''+selector+'').attr('data-product-name', product_name);
		$(''+selector+'').attr('data-attribute-name', attribute_name);
		if(product_options_json.length > 0 && product_options_json !== undefined && product_options_json !== '[]') {
			prod_json = ""+product_options_json+"";
		} else {
			prod_json = '';
		}

		if(product_extras_json.length > 0 && product_extras_json !== undefined && product_extras_json !== '[]') {
			prodex_json = ""+product_extras_json+"";
			extrakes = JSON.parse(product_extras_json);
			extras_price = 0;
			for (var i = 0; i < extrakes.length; i++) {
				if(!$.isEmptyObject(extrakes[i]) ) {
					extras_price = extras_price + parseFloat(extrakes[i]['value']);
				}
				
			};
			console.log('extrassss price :', extras_price);
		} else {
			prodex_json = '';
			extras_price = 0;
		}

		current_price = parseFloat(current_price) + extras_price;
		total_price = parseFloat(total_price) + (quantity * extras_price);

		$(''+selector+'').attr('data-product-options', prod_json);
		$(''+selector+'').attr('data-product-extras', prodex_json);
		$(''+selector+'').attr('data-quantity', quantity);
		$(''+selector+'').attr('data-unit-price', current_price);
		$(''+selector+'').attr('data-total-price', total_price);
		$(''+selector+'').attr('data-unique-el', '');

		if(attribute_name == '' || attribute_name == undefined) {
			full_name = product_name;
		} else {
			full_name = product_name + ' - ' + attribute_name;
		}

		$(''+selector+'').find('.cof_cartProductListItemOptions:not(:first)').remove();
		$(''+selector+'').find('.cof_cartProductListItemOptions:first').removeClass('d-block').addClass('d-none');

		if(product_options_json !== '' && product_options_json !== undefined && product_options_json !== '[]') {
			product_options = JSON.parse(product_options_json);

			$(''+selector+'').find('.cof_cartProductListItemOptions:last').addClass('d-block').removeClass('d-none');

			for (var i = 0; i < product_options.length; i++) {
				if(i > 0) {
					$(''+selector+'').find('.cof_cartProductListItemOptions:first').clone().appendTo($(''+selector+'').find('.cof_cartProductListDetails:first'));
				}

				$(''+selector+'').find('.cof_cartProductListItemOptions:last').find('.cof_cartProductListItemOptionName').text(product_options[i]['name']);
				$(''+selector+'').find('.cof_cartProductListItemOptions:last').find('.cof_cartProductListItemOptionValue').text(product_options[i]['value']);

			};
		} 



		$(''+selector+'').find('.cof_cartProductListItemExtras:not(:first)').remove();
		$(''+selector+'').find('.cof_cartProductListItemExtras:first').appendTo($(''+selector+'').find('.cof_cartProductListDetails:first'));
		$(''+selector+'').find('.cof_cartProductListItemExtras:first').removeClass('d-block').addClass('d-none');

		if(product_extras_json !== '' && product_extras_json !== undefined && product_extras_json !== '[]') {
			product_extras = JSON.parse(product_extras_json);

			$(''+selector+'').find('.cof_cartProductListItemExtras:last')
								.addClass('d-block')
								.removeClass('d-none');

			extra_faulty_check = false;
			g = 0;
			for (var i = 0; i < product_extras.length; i++) {
				console.log('check ot the ',i,' th : ', product_extras[i]);
				if(!$.isEmptyObject(product_extras[i])) {
					extra_faulty_check = true;
					if(g > 0) {
						$(''+selector+'').find('.cof_cartProductListItemExtras:first').clone().appendTo($(''+selector+'').find('.cof_cartProductListDetails:first'));
					}

					$(''+selector+'').find('.cof_cartProductListItemExtras:last').find('.cof_cartProductListItemOptionName').text(product_extras[i]['name']);
					$(''+selector+'').find('.cof_cartProductListItemExtras:last').find('.cof_cartProductListItemOptionValue').text( '€ '+parseFloat(product_extras[i]['value']).toFixed(2).replace('.', ',') );

					g++;
				}

			};

			if(!extra_faulty_check) {
				$(''+selector+'').find('.cof_cartProductListItemExtras:last').addClass('d-none').removeClass('d-block');
			}
		} 

		$(''+selector+'').find('.cof_cartProductListItemFullName').text(full_name);
		$(''+selector+'').find('.cof_cartProductListItemQuantity').text(quantity);
		$(''+selector+'').find('.cof_cartProductListItemUnitPrice').text('€ '+parseFloat(current_price).toFixed(2).replace('.', ','));
		$(''+selector+'').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));
	}

	function updateProductListItemQuantity(selector, quantity, total_price)
	{	
		newquantity = parseInt($(''+selector+'').attr('data-quantity')) + parseInt(quantity);
		$(''+selector+'').attr('data-quantity', newquantity);
		$(''+selector+'').find('.cof_cartProductListItemQuantity').text(newquantity);
		
		new_total_price = parseFloat($(''+selector+'').attr('data-total-price')) + parseFloat(total_price);
		$(''+selector+'').attr('data-total-price', new_total_price);
		$(''+selector+'').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(new_total_price).toFixed(2).replace('.', ','));

		$(''+selector+'').attr('data-unique-el', '');
	}

	function resetOptionsModal()
	{
		$('.options_modal_row:not(:first)').remove();
		$('.options_modal_row:first').find('.cof_options_radio_item_input:not(:first)').remove();
		$('.options_modal_row:first').find('.cof_options_select_item_input').find('option:not(:first)').remove();
		$('.options_modal_row:first').attr('data-product-option-type', '');
		$('.options_modal_row:first').attr('data-product-option-name', '');
		$('.options_modal_row:first').find('.cof_options_radio_item_input:first').find('input').attr('name', 'cof_options_radio');

		$('.extras_modal_row:not(:first)').remove();
		$('.extras_modal_row:first').find('.extras_item_name').text('cof_extra_name');
		$('.extras_modal_row:first').find('.extras_item_name').attr('for', 'cof_extra_name');
		$('.extras_modal_row:first').find('.extras_item_checkbox').attr('id', 'cof_extra_name');
		$('.extras_modal_row:first').find('.extras_item_checkbox').val('');
		$('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-price', '');
	}

	function setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_options, product_extras)
	{
		$('.options_product_name').text(product_name);
		$('#addProductWithOptionsToCartButton').attr('data-product-id', product_id);
		$('#addProductWithOptionsToCartButton').attr('data-current-price', current_price);
		$('#addProductWithOptionsToCartButton').attr('data-quantity', quantity);
		$('#addProductWithOptionsToCartButton').attr('data-total-price', total_price);

		radio_ids = [];

		if(product_options.length > 0) {
			$('#optionsModalBody').removeClass('d-none');
			for (var i = 0; i < product_options.length; i++) {
				if(i > 0) {
					$('.options_modal_row:first').clone().appendTo('#optionsModalBody');
				}

				option_name = product_options[i]['name'];
				option_type = product_options[i]['type'];
				option_values = product_options[i]['values'].split(',');
				
				$('.options_modal_row:last').attr('data-product-option-type', option_type);
				$('.options_modal_row:last').attr('data-product-option-name', option_name);
				$('.options_modal_row:last').find('.options_item_name').text(option_name);
				
				if(option_type == 'radio') {
					$('.options_modal_row:last').find('.options_modal_item_radio').removeClass('d-none hidden');
					$('.options_modal_row:last').find('.options_modal_item_select').addClass('d-none hidden');

					$('.options_modal_row:last').find('.cof_options_radio_item_input:not(:first)').remove();

					$('.options_modal_row:last').attr('data-product-option-input-name', 'cof_options_radio_'+i);

					for (var k = 0; k < option_values.length; k++) {
						if(k > 0) {
							$('.options_modal_row:last').find('.cof_options_radio_item_input:first').clone().appendTo('.options_modal_row:last .cof_options_radio_item_input_group');
						}
						$('.options_modal_row:last')
							.find('.cof_options_radio_item_input:last input:first').first()
							.val(option_values[k])
							.attr('id', 'cofOptionsRadioId'+i+'_'+k)
							.attr('name', 'cof_options_radio_'+i)
							.attr('checked', false);
						$('.options_modal_row:last').find('.cof_options_radio_item_input:last').find('span').text(option_values[k]).attr('for', 'cofOptionsRadioId'+i+'_'+k);
						$('.options_modal_row:last').find('.cof_options_radio_item_input:last').find('label').attr('for', 'cofOptionsRadioId'+i+'_'+k);

						if(k == 0) {
							radio_ids.push('#cofOptionsRadioId'+i+'_'+k);
						}

					}

				} 
				if(option_type == 'select') {
					$('.options_modal_row:last').find('.options_modal_item_radio').addClass('d-none hidden');
					$('.options_modal_row:last').find('.options_modal_item_select').removeClass('d-none hidden');

					$('.options_modal_row:last').find('.cof_options_select_item_input').attr('name', 'cof_options_select'+i);
					$('.options_modal_row:last').find('.cof_options_select_item_input').attr('id', 'cof_options_selectId'+i);

					$('#cof_options_selectId'+i).find('.cof_options_option_input:not(:first)').remove();
					$('.options_modal_row:last').find('.cof_options_radio_item_input:not(:first)').remove();
					$('.options_modal_row:last').find('.cof_options_radio_item_input:first').attr('name', 'cof_options_radio').attr('id', '');

					$('.options_modal_row:last').find('.cof_options_radio_item_input:first').find('label:first').attr('for', '');
					$('.options_modal_row:last').find('.cof_options_radio_item_input:first').find('input:first').attr('name', 'cof_options_radio').attr('id', '').val('');

					$('.options_modal_row:last').attr('data-product-option-input-name', 'cof_options_select'+i);

					for (var j = 0; j < option_values.length; j++) {
						if(j > 0) {
							$('#cof_options_selectId'+i+' .cof_options_option_input:first').first().clone().appendTo('#cof_options_selectId'+i);
						}

						$('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().val(option_values[j]);
						$('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().text(option_values[j]);
						$('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().prop('selected', false);

					}
					$('#cof_options_selectId'+i).val($('#cof_options_selectId'+i).find('option').first().val());
				}
				
			};

			for (var i = 0; i < radio_ids.length; i++) {
				$(''+radio_ids[i]+'').prop('checked', true);
			};
			
		} else {
			$('#optionsModalBody').addClass('d-none');
		}


		if(product_extras.length > 0) {
			$('#extrasModalBody').removeClass('d-none');
			for (var i = 0; i < product_extras.length; i++) {
				if(i > 0) {
					$('.extras_modal_row:first').clone().appendTo('#extrasModalBody');
				}

				extra_name = product_extras[i]['name'];
				extra_slug = extra_name.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
				extra_price = product_extras[i]['price'];
				
				//$('.extras_modal_row:last').attr('data-product-option-type', option_type);
				//$('.extras_modal_row:last').attr('data-product-option-name', option_name);
				$('.extras_modal_row:last').find('.extras_item_name')
								.text( extra_name + ' (€ ' + parseFloat(extra_price).toFixed(2).replace('.', ',') + ')' );
				$('.extras_modal_row:last').find('.extras_item_name').attr('for', extra_slug);
				$('.extras_modal_row:last').find('.extras_item_checkbox').attr('id', extra_slug);
				$('.extras_modal_row:last').find('.extras_item_checkbox').val(extra_name);
				$('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-price', parseFloat(extra_price));
				$('.extras_modal_row:last').find('.extras_item_checkbox').prop('checked', false);
				
				
				
			};
			
		} else {
			$('#extrasModalBody').addClass('d-none');
		}
		
	}

	$('body').on('click', '#options-form .options_modal_item_radio label.form-check-label', function (event) {
		$(this).siblings('input').first().prop('checked', true);
	});

	function updateCartCount(cart_count) {
		$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count);
		$('#cof_cartTotalQuanity').text(cart_count);
		$('.cof_cartTotalQuanity').text(cart_count);
	}

	function calculateTotalPrice() {
		total_price = getTotalPrice();
		shipping_price = calculateShippingPrice();

		total_with_shipping_price = total_price + shipping_price;
		$('.cof_cartTotalPrice').text('€ '+parseFloat(total_with_shipping_price).toFixed(2).replace('.', ','));

		has_mop = $('#cof_orderBtnCard').attr('data-has-mop');
		if(has_mop == true){
			mop = $('#cof_orderBtnCard').attr('data-mop');
			if(total_price >= mop) {
				$('#cof_minOrderP_not').hide();
				$('#cof_placeOrderBtnNow').prop('disabled', false);
			} else {
				$('#cof_minOrderP_not').show();
				$('#cof_placeOrderBtnNow').prop('disabled', true);
			}
		}
	}

	function calculateShippingPrice() {
		if($('.cof_location_radio:checked').attr('data-location-type') == 'delivery') {
			$('#cof_CartProductListShippingLine').removeClass('d-none');
			$('#cof_CartProductListShippingLine').removeClass('hidden');
		} else {
			$('#cof_CartProductListShippingLine').addClass('d-none');
			$('#cof_CartProductListShippingLine').addClass('hidden');
		}

		location_shipping_price = $('.cof_location_radio:checked').attr('data-delivery-cost');
		$('.cof_cartShippingPrice').text('€ '+parseFloat(location_shipping_price).toFixed(2).replace('.', ','));

		return parseFloat(location_shipping_price);
	}

	function calculateProductQty() {
		locationKey = $('.cof_location_radio:checked').attr('data-location-key');
		$('.cof_additionProductBtn').each(function() {
			productId = $(this).attr('data-product-id');
			q = $(this).attr('data-q').split(',')
			for (var i = 0; i < q.length; i++) {
				if(q[i].search(''+locationKey+'=') !== -1) {
					max_q = q[i].split('=').pop();
					$(this).attr('data-max-q', max_q);
					if(parseInt(max_q) == 0) {
						$('.cof_btnAddProductToCart[data-product-id='+productId+']').prop('disabled', true);
						$('.cof_btnAddProductOptionsToCart[data-product-id='+productId+']').prop('disabled', true);
						$('.cof_btnAddProductAttributeToCart[data-product-id='+productId+']').prop('disabled', true);
						$('.cof_btnAddProductAttributeOptionsToCart[data-product-id='+productId+']').prop('disabled', true);
					} else {
						$('.cof_btnAddProductToCart[data-product-id='+productId+']').prop('disabled', false);
						$('.cof_btnAddProductOptionsToCart[data-product-id='+productId+']').prop('disabled', false);
						$('.cof_btnAddProductAttributeToCart[data-product-id='+productId+']').prop('disabled', false);
						$('.cof_btnAddProductAttributeOptionsToCart[data-product-id='+productId+']').prop('disabled', false);
					}
				}
			};
		});
		//@TODO: add a check for products already in cart
	}

	function isAddressEligible() {
		to_address = $('input[name=order_street]').val()+' '+$('input[name=order_housenumber]').val()+' '+$('input[name=order_postalcode]').val()+' '+$('input[name=order_city]').val();

		return $.ajax({
            method: 'POST',
            url: is_address_eligible_url,
            data: { 
            	locationKey: $('input[name=location]:checked').val(), 
            	to: to_address,
            	postalcode: $('input[name=order_postalcode]').val(),
            	_token: a_token
            }
        });
	}

	function getTotalPrice() {
		total_price = 0;
		$('.cof_cartProductListItem').each(function() {
			total_price = total_price + parseFloat($(this).attr('data-total-price'));
		});
		return total_price;
	}

	function animateImgToCart(cart, imgtodrag, quantity)
	{
		var imgclone = imgtodrag.clone()
            .offset({
            top: imgtodrag.offset().top,
            left: imgtodrag.offset().left
        })
            .css({
            'opacity': '0.5',
                'position': 'absolute',
                'height': '150px',
                'width': '150px',
                'z-index': '100'
        })
            .appendTo($('body'))
            .animate({
            'top': cart.offset().top + 10,
                'left': cart.offset().left + 10,
                'width': 75,
                'height': 75
        }, 1000, 'easeInOutExpo');
        
        setTimeout(function () {
            cart.effect("shake", {
                times: 2
            }, 200);
            $('.cof_cartTotalQuanity').text(parseInt(quantity));
        }, 1500);

        imgclone.animate({
            'width': 0,
                'height': 0
        }, function () {
            $(this).detach()
        });
	}
});

function scrollToCart() {
	if(window.innerWidth < 990) {
		w = 15;
	} else {
		w = 90;
	}
	$('html, body').animate({
        scrollTop: $('#cof_orderFormCartSection').offset().top - w
  	}, 800);
}
</script>