@if(config('chuckcms-module-order-form.cart.use_ui') == true)
<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
@endif

@if(config('chuckcms-module-order-form.datepicker.js.use') == true)
<script src="{{ config('chuckcms-module-order-form.datepicker.js.asset') ? asset(config('chuckcms-module-order-form.datepicker.js.link')) : config('chuckcms-module-order-form.datepicker.js.link') }}"></script>
<script src="{{ config('chuckcms-module-order-form.datepicker.js.locale_asset') ? asset(config('chuckcms-module-order-form.datepicker.js.locale_link')) : config('chuckcms-module-order-form.datepicker.js.locale_link') }}"></script>
@endif


<script type="text/javascript">
	var cof_dp_startdate = {};
</script>

@foreach(config('chuckcms-module-order-form.locations') as $locationKey => $location)
<script type="text/javascript">
	cof_dp_startdate['{{ $locationKey }}'] = '+{{ ChuckModuleOrderForm::firstAvailableDateInDaysFromNow($locationKey) }}d';
</script>
@endforeach
<script type="text/javascript">
var order_url = "{{ route('cof.place_order') }}";
var a_token = "{{ Session::token() }}";
if (!$.fn.bootstrapDP && $.fn.datepicker && $.fn.datepicker.noConflict) 
{
var datepicker = $.fn.datepicker.noConflict();
$.fn.bootstrapDP = datepicker;
}
</script>


<script type="text/javascript">
$(document).ready(function() {
	var OGlocationKey = $('.cof_location_radio:checked').attr('data-location-key');
	var OGlocationDate = $('.cof_location_radio:checked').attr('data-first-available-date');

	$('.cof_datepicker').val(OGlocationDate);

	$('.cof_datepicker').bootstrapDP({
	    format: 'dd/mm/yyyy',
	    startDate: cof_dp_startdate[OGlocationKey],
	    weekStart: 1,
	    language: "{{ config('chuckcms-module-order-form.datepicker.js.locale') }}",
	    daysOfWeekDisabled: "{{ config('chuckcms-module-order-form.locations.afhalen.days_of_week_disabled') }}"
	});

	$('body').on('change', '.cof_location_radio', function (event) {
		var locationKey = $('.cof_location_radio:checked').attr('data-location-key');
		var locationDate = $('.cof_location_radio:checked').attr('data-first-available-date');
		var locationDaysOfWeekDisabled = $('.cof_location_radio:checked').attr('data-days-of-week-disabled');

		$('.cof_datepicker').bootstrapDP('destroy');
		$('.cof_datepicker').val(locationDate);
		$('.cof_datepicker').bootstrapDP({
		    format: 'dd/mm/yyyy',
		    startDate: cof_dp_startdate[locationKey],
		    weekStart: 1,
		    language: "{{ config('chuckcms-module-order-form.datepicker.js.locale') }}",
		    daysOfWeekDisabled: locationDaysOfWeekDisabled
		});
	});

	$('body').on('click', '.cof_additionProductBtn', function (event) {
		event.preventDefault();
		product_id = $(this).attr('data-product-id');
		newValue = parseInt($('.cof_productQuantityInput[data-product-id='+product_id+']').val()) + 1;
		$('.cof_productQuantityInput[data-product-id='+product_id+']').val(newValue);
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

			$('.cof_cartProductListItem:first').attr('data-product-id', product_id);
			$('.cof_cartProductListItem:first').attr('data-product-name', product_name);
			$('.cof_cartProductListItem:first').attr('data-quantity', quantity);
			$('.cof_cartProductListItem:first').attr('data-unit-price', current_price);
			$('.cof_cartProductListItem:first').attr('data-total-price', total_price);

			$('.cof_cartProductListItem:first').find('.cof_cartProductListItemFullName').text(product_name);
			$('.cof_cartProductListItem:first').find('.cof_cartProductListItemQuantity').text(quantity);
			$('.cof_cartProductListItem:first').find('.cof_cartProductListItemUnitPrice').text('€ '+parseFloat(current_price).toFixed(2).replace('.', ','));
			$('.cof_cartProductListItem:first').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));
		} else {
			if($('.cof_cartProductListItem[data-product-id='+product_id+']').length > 0) {
				newquantity = parseInt($('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-quantity')) + parseInt(quantity);
				$('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-quantity', newquantity);
				$('.cof_cartProductListItem[data-product-id='+product_id+']').find('.cof_cartProductListItemQuantity').text(newquantity);
				new_total_price = parseFloat($('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-total-price')) + parseFloat(total_price);
				$('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-total-price', new_total_price);
				$('.cof_cartProductListItem[data-product-id='+product_id+']').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(new_total_price).toFixed(2).replace('.', ','));
			} else {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				$('.cof_cartProductListItem:last').attr('data-product-id', product_id);
				$('.cof_cartProductListItem:last').attr('data-product-name', product_name);
				$('.cof_cartProductListItem:last').attr('data-attribute-name', '');
				$('.cof_cartProductListItem:last').attr('data-quantity', quantity);
				$('.cof_cartProductListItem:last').attr('data-unit-price', current_price);
				$('.cof_cartProductListItem:last').attr('data-total-price', total_price);

				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemFullName').text(product_name);
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemQuantity').text(quantity);
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemUnitPrice').text('€ '+parseFloat(current_price).toFixed(2).replace('.', ','));
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));
			}
		}

		$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count + parseInt(quantity));
		$('#cof_cartTotalQuanity').text(cart_count + parseInt(quantity));
		$('.cof_cartTotalQuanity').text(cart_count + parseInt(quantity));
		$('.cof_productQuantityInput[data-product-id='+product_id+']').val(1);

		var cart = $('.cof_cartIconLeftCorner');
        var imgtodrag = $('.cof_productImage[data-product-id='+product_id+']').eq(0);
        if (imgtodrag.length) {
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
                $('.cof_cartTotalQuanity').text(cart_count + parseInt(quantity));
            }, 1500);

            imgclone.animate({
                'width': 0,
                    'height': 0
            }, function () {
                $(this).detach()
            });
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

			$('.cof_cartProductListItem:first').attr('data-product-id', product_id);
			$('.cof_cartProductListItem:first').attr('data-product-name', product_name);
			$('.cof_cartProductListItem:first').attr('data-attribute-name', attribute_name);
			$('.cof_cartProductListItem:first').attr('data-quantity', quantity);
			$('.cof_cartProductListItem:first').attr('data-unit-price', current_price);
			$('.cof_cartProductListItem:first').attr('data-total-price', total_price);

			$('.cof_cartProductListItem:first').find('.cof_cartProductListItemFullName').text(product_name + ' - ' + attribute_name);
			$('.cof_cartProductListItem:first').find('.cof_cartProductListItemQuantity').text(quantity);
			$('.cof_cartProductListItem:first').find('.cof_cartProductListItemUnitPrice').text('€ '+parseFloat(current_price).toFixed(2).replace('.', ','));
			$('.cof_cartProductListItem:first').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));
		} else {
			
			if($('.cof_cartProductListItem[data-product-id='+product_id+']').length == 1 && attribute_name == $('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-attribute-name')) {
				newquantity = parseInt($('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-quantity')) + parseInt(quantity);
				$('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-quantity', newquantity);
				$('.cof_cartProductListItem[data-product-id='+product_id+']').find('.cof_cartProductListItemQuantity').text(newquantity);
				new_total_price = parseFloat($('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-total-price')) + parseFloat(total_price);
				$('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-total-price', new_total_price);
				$('.cof_cartProductListItem[data-product-id='+product_id+']').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(new_total_price).toFixed(2).replace('.', ','));
			} else if($('.cof_cartProductListItem[data-product-id='+product_id+']').length == 0) {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				$('.cof_cartProductListItem:last').attr('data-product-id', product_id);
				$('.cof_cartProductListItem:last').attr('data-product-name', product_name);
				$('.cof_cartProductListItem:last').attr('data-attribute-name', attribute_name);
				$('.cof_cartProductListItem:last').attr('data-quantity', quantity);
				$('.cof_cartProductListItem:last').attr('data-unit-price', current_price);
				$('.cof_cartProductListItem:last').attr('data-total-price', total_price);

				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemFullName').text(product_name + ' - ' + attribute_name);
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemQuantity').text(quantity);
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemUnitPrice').text('€ '+parseFloat(current_price).toFixed(2).replace('.', ','));
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));
			} else if($('.cof_cartProductListItem[data-product-id='+product_id+']').length == 1 && attribute_name !== $('.cof_cartProductListItem[data-product-id='+product_id+']').attr('data-attribute-name')) {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				$('.cof_cartProductListItem:last').attr('data-product-id', product_id);
				$('.cof_cartProductListItem:last').attr('data-product-name', product_name);
				$('.cof_cartProductListItem:last').attr('data-attribute-name', attribute_name);
				$('.cof_cartProductListItem:last').attr('data-quantity', quantity);
				$('.cof_cartProductListItem:last').attr('data-unit-price', current_price);
				$('.cof_cartProductListItem:last').attr('data-total-price', total_price);

				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemFullName').text(product_name + ' - ' + attribute_name);
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemQuantity').text(quantity);
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemUnitPrice').text('€ '+parseFloat(current_price).toFixed(2).replace('.', ','));
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));
			} else if($('.cof_cartProductListItem[data-product-id='+product_id+']').length > 1 && $('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').length > 0) {
				newquantity = parseInt($('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').attr('data-quantity')) + parseInt(quantity);
				$('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').attr('data-quantity', newquantity);
				$('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').find('.cof_cartProductListItemQuantity').text(newquantity);
				new_total_price = parseFloat($('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').attr('data-total-price')) + parseFloat(total_price);
				$('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').attr('data-total-price', new_total_price);
				$('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(new_total_price).toFixed(2).replace('.', ','));
			} else if($('.cof_cartProductListItem[data-product-id='+product_id+']').length > 1 && $('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').length == 0) {
				$('.cof_cartProductListItem:first').clone().appendTo('#cof_CartProductList');

				$('.cof_cartProductListItem:last').attr('data-product-id', product_id);
				$('.cof_cartProductListItem:last').attr('data-product-name', product_name);
				$('.cof_cartProductListItem:last').attr('data-attribute-name', attribute_name);
				$('.cof_cartProductListItem:last').attr('data-quantity', quantity);
				$('.cof_cartProductListItem:last').attr('data-unit-price', current_price);
				$('.cof_cartProductListItem:last').attr('data-total-price', total_price);

				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemFullName').text(product_name + ' - ' + attribute_name);
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemQuantity').text(quantity);
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemUnitPrice').text('€ '+parseFloat(current_price).toFixed(2).replace('.', ','));
				$('.cof_cartProductListItem:last').find('.cof_cartProductListItemTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

				$('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));
			}

		}

		$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count + parseInt(quantity));
		$('#cof_cartTotalQuanity').text(cart_count + parseInt(quantity));
		$('.cof_productQuantityInput[data-product-id='+product_id+']').val(1);


        var cart = $('.cof_cartIconLeftCorner');
        var imgtodrag = $('.cof_productImage[data-product-id='+product_id+']').eq(0);
        if (imgtodrag.length) {
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
                $('.cof_cartTotalQuanity').text(cart_count + parseInt(quantity));
            }, 1500);

            imgclone.animate({
                'width': 0,
                    'height': 0
            }, function () {
                $(this).detach()
            });
        }
    

		calculateTotalPrice();
	});






	
	$('body').on('click', '.cof_deleteProductFromListButton', function (event) {
		event.preventDefault();
		product_id = $(this).closest('.cof_cartProductListItem').attr('data-product-id');
		attribute_name = $(this).closest('.cof_cartProductListItem').attr('data-attribute-name');

		if($('.cof_cartProductListItem').length > 1) {
			product_quantity = $('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').attr('data-quantity');
			$('.cof_cartProductListItem[data-product-id='+product_id+'][data-attribute-name="'+attribute_name+'"]').remove();

			cart_count = $('#cof_cartTotalQuanity').attr('data-cof-quantity');
			$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count - parseInt(product_quantity));
			$('#cof_cartTotalQuanity').text(cart_count - parseInt(product_quantity));
			$('.cof_cartTotalQuanity').text(cart_count - parseInt(product_quantity));
		} else if( $('.cof_cartProductListItem').length == 1) {
			$('#cof_CartProductList').hide();
			$('#cof_emptyCartNotice').show();

			$('.cof_cartProductListItem:first').attr('data-product-id', 0);
			$('.cof_cartProductListItem:first').attr('data-quantity', 0);
			$('.cof_cartProductListItem:first').attr('data-product-name', '');
			$('.cof_cartProductListItem:first').attr('data-attribute-name', '');
			$('.cof_cartProductListItem:first').attr('data-unit-price', 0);
			$('.cof_cartProductListItem:first').attr('data-total-price', 0);

			$('#cof_cartTotalQuanity').attr('data-cof-quantity', 0);
			$('#cof_cartTotalQuanity').text('0');
			$('.cof_cartTotalQuanity').text('0');
		}


		calculateTotalPrice();
		

	});

	$('body').on('click', '#cof_placeOrderBtnNow', function (event) {
		$('.error_bag:first').addClass('hidden');

		if( !validateForm() ) {
			$('.error_span:first').html(' Niet alle verplichte velden zijn ingevuld...');
			$('.error_bag:first').removeClass('hidden');
			return false;
		}

		if( $('input[type=checkbox][name=legal_approval]:checked').length == 0 ) {
			$('.error_span:first').html(' Uw goedkeuring is vereist om deze bestelling te kunnen plaatsen.');
			$('.error_bag:first').removeClass('hidden');
			return false;
		}
		
        var products = getProducts();
        var price = getTotalPrice();
		// $('input[type=number]').each(function() {
  //  			var elem = $(this);
	 //      	// If value has changed and is not empty...
	 //      	if (elem.val() && elem.val() > 0) {
	 //      		console.log('price :: ',elem.attr('data-price'));
		// 		price += (Number(elem.attr('data-price')) * Number(elem.val()));
	 //    	}

		// });

		if ( price == 0.00 ) {
			$('.error_span:first').html(' U heeft geen producten geselecteerd...');
			$('.error_bag:first').removeClass('hidden');
			return false;
		}

		$(this).html('Even geduld ...');
        $(this).prop('disabled', true);

        $.ajax({
            method: 'POST',
            url: order_url,
            data: { 
            	location: $('input[name=location]:checked').val(), 
            	order_date: $('input[name=order_date]').val(), 
            	surname: $('input[name=order_surname]').val(), 
            	name: $('input[name=order_name]').val(),
            	email: $('input[name=order_email]').val(),
            	tel: $('input[name=order_tel]').val(),
            	street: $('input[name=order_street]').val(),
            	housenumber: $('input[name=order_housenumber]').val(),
            	postalcode: $('input[name=order_postalcode]').val(),
            	city: $('input[name=order_city]').val(),
            	order: products,
            	total: price,
            	legal_approval: $('input[name=legal_approval]').val(),
            	promo_approval: $('input[name=promo_approval]').val(),
            	_token: a_token
            }
        })
        .done(function(data) {
        	console.log( 'data :: ', data);
            if (data.status == "success"){
                window.location.href = data.url;
            }
            else{
                $(this).html('Bestellen');
        		$(this).prop('disabled', false);

        		$('.error_span:first').html(' Er is iets misgelopen, probeer het later nog eens!');
				$('.error_bag:first').removeClass('hidden');
            }
        });
	});

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
    	var products = {};
		$('.cof_cartProductListItem').each(function() {
   			var elem = $(this);
	      	// If value has changed and is not empty...
	      	if (elem.attr('data-attribute-name') == '') {
console.log('prodcut name :: ',elem.attr('data-product-name'));
	      		var key = elem.attr('data-product-id');
				products[key] = { 
					attributes: false, 
					name: elem.attr('data-product-name'), 
					price: Number(elem.attr('data-unit-price')),
					qty: Number(elem.attr('data-quantity')),
					totprice: Number(elem.attr('data-total-price'))
				};
	    	}

	    	if (elem.attr('data-is-attribute') !== '') {
	      		//console.log('price :: ',elem.attr('data-price'));
	      		var key = elem.attr('data-product-id');
	      		if(!products.hasOwnProperty(key)){
	      			products[key] = {attributes: true} 
	      		}
				var keyAttr = convertToSlug(elem.attr('data-attribute-name'));
				products[key][keyAttr] = {
					name: elem.attr('data-product-name') + ' - ' + elem.attr('data-attribute-name'), 
					price: Number(elem.attr('data-unit-price')),
					qty: Number(elem.attr('data-quantity')),
					totprice: Number(elem.attr('data-total-price'))
				};
	    	}
		});
		return products;
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

	function calculateTotalPrice() {
		total_price = 0;
		$('.cof_cartProductListItem').each(function() {
			total_price = total_price + parseFloat($(this).attr('data-total-price'));
		});
		$('.cof_cartTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

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

	function getTotalPrice() {
		total_price = 0;
		$('.cof_cartProductListItem').each(function() {
			total_price = total_price + parseFloat($(this).attr('data-total-price'));
		});
		return total_price;
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