<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
		
		Route::get('/dashboard/order-form', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderFormController@index')->name('dashboard.module.order_form.index');
		
		//START OF: ORDERS ROUTES
		Route::get('/dashboard/order-form/orders', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@index')->name('dashboard.module.order_form.orders.index');
		Route::get('/dashboard/order-form/orders/excel', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@excel')->name('dashboard.module.order_form.orders.excel');
		Route::get('/dashboard/order-form/orders/pdf', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@pdf')->name('dashboard.module.order_form.orders.pdf');
		Route::get('/dashboard/order-form/orders/{order}/detail', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@detail')->name('dashboard.module.order_form.orders.detail');
		Route::post('/dashboard/order-form/orders/update_date', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@updateDate')->name('dashboard.module.order_form.products.update_date');
		Route::post('/dashboard/order-form/orders/update_address', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@updateAddress')->name('dashboard.module.order_form.products.update_address');
		Route::post('/dashboard/order-form/orders/resend_confirmation', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@resendConfirmation')->name('dashboard.module.order_form.orders.resend_confirmation');
		//END OF:   ORDERS ROUTES
		
		//START OF: CATEGORIES ROUTES
		Route::get('/dashboard/order-form/categories', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\CategoryController@index')->name('dashboard.module.order_form.categories.index');
		Route::post('/dashboard/order-form/categories/save', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\CategoryController@save')->name('dashboard.module.order_form.categories.save');
		Route::post('/dashboard/order-form/categories/delete', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\CategoryController@delete')->name('dashboard.module.order_form.categories.delete');
		//END OF:   CATEGORIES ROUTES
		
		//START OF: COUPONS ROUTES
		Route::get('/dashboard/order-form/coupons', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\CouponController@index')->name('dashboard.module.order_form.coupons.index');
		Route::post('/dashboard/order-form/coupons/save', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\CouponController@save')->name('dashboard.module.order_form.coupons.save');
		Route::post('/dashboard/order-form/coupons/delete', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\CouponController@delete')->name('dashboard.module.order_form.coupons.delete');
		//END OF:   COUPONS ROUTES
		
		//START OF: DISCOUNTS ROUTES
		Route::get('/dashboard/order-form/discounts', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\DiscountController@index')->name('dashboard.module.order_form.discounts.index');
		Route::get('/dashboard/order-form/discounts/create', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\DiscountController@create')->name('dashboard.module.order_form.discounts.create');
		Route::get('/dashboard/order-form/discounts/{discount}/edit', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\DiscountController@edit')->name('dashboard.module.order_form.discounts.edit');
		Route::post('/dashboard/order-form/discounts/save', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\DiscountController@save')->name('dashboard.module.order_form.discounts.save');
		Route::post('/dashboard/order-form/discounts/delete', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\DiscountController@delete')->name('dashboard.module.order_form.discounts.delete');
		Route::post('/dashboard/order-form/discounts/refresh/code', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\DiscountController@refreshCode')->name('dashboard.module.order_form.discounts.refresh_code');
		//END OF: DISCOUNTS ROUTES
		
		//START OF: PRODUCTS ROUTES
		Route::get('/dashboard/order-form/products', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@index')->name('dashboard.module.order_form.products.index');
		Route::get('/dashboard/order-form/products/create', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@create')->name('dashboard.module.order_form.products.create');
		Route::get('/dashboard/order-form/products/{product}/edit', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@edit')->name('dashboard.module.order_form.products.edit');
		Route::post('/dashboard/order-form/products/save', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@save')->name('dashboard.module.order_form.products.save');
		Route::post('/dashboard/order-form/products/delete', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@delete')->name('dashboard.module.order_form.products.delete');
		Route::post('/dashboard/order-form/products/update', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@update')->name('dashboard.module.order_form.products.update');
		//END OF: PRODUCTS ROUTES
		
		//START OF: PRODUCTS ROUTES
		Route::get('/dashboard/order-form/customers', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\CustomerController@index')->name('dashboard.module.order_form.customers.index');
		Route::get('/dashboard/order-form/customers/{customer}', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\CustomerController@detail')->name('dashboard.module.order_form.customers.detail');
		//END OF:   PRODUCTS ROUTES
		
		//START OF: REWARDS ROUTES
		Route::get('/dashboard/order-form/rewards', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\RewardController@index')->name('dashboard.module.order_form.rewards.index');
		Route::post('/dashboard/order-form/rewards/save', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\RewardController@save')->name('dashboard.module.order_form.rewards.save');
		Route::post('/dashboard/order-form/rewards/delete', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\RewardController@delete')->name('dashboard.module.order_form.rewards.delete');
		//END OF:   REWARDS ROUTES

		//START OF: LOCATIONS ROUTES
		Route::get('/dashboard/order-form/locations', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\LocationController@index')->name('dashboard.module.order_form.locations.index');
		Route::post('/dashboard/order-form/locations/save', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\LocationController@save')->name('dashboard.module.order_form.locations.save');
		Route::post('/dashboard/order-form/locations/delete', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\LocationController@delete')->name('dashboard.module.order_form.locations.delete');
		//END OF:   LOCATIONS ROUTES
		
		//START OF: SETTINGS ROUTES
		Route::get('/dashboard/order-form/settings', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\SettingsController@index')->name('dashboard.module.order_form.settings.index');
		Route::post('/dashboard/order-form/settings', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\SettingsController@update')->name('dashboard.module.order_form.settings.update');

		//START OF: POS ROUTES
		Route::get('/dashboard/order-form/pos', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\POSController@index')->name('dashboard.module.order_form.pos.index');
		Route::get('/dashboard/order-form/pos/data', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\POSController@list')->name('dashboard.module.order_form.pos.list');
		Route::post('/dashboard/order-form/pos/place-order', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\POSController@postOrder')->name('dashboard.module.order_form.pos.place_order');

		Route::group(['middleware' => 'role:customer'], function() {
			Route::get('/mijn-account', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\AccountController@index')->name('dashboard.module.order_form.account.index');
			Route::get('/mijn-account/coupons', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\AccountController@coupons')->name('dashboard.module.order_form.account.coupons');
			Route::get('/mijn-account/punten-inruilen', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\AccountController@swapPoints')->name('dashboard.module.order_form.account.swap_points');

			Route::post('/mijn-account/punten-inruilen/swap', 'RewardController@swap')->name('swap.points');
		});
	});

	Route::post('/cof/place-order', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@postOrder')->name('cof.place_order');
	Route::post('/cof/get-status', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@orderStatus')->name('cof.status');
	Route::post('/cof/is-address-eligible', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ShippingController@isAddressEligible')->name('cof.is_address_eligible');

	Route::get('{order_number}/bedankt-voor-uw-bestelling', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@orderFollowup')->name('cof.followup');
	Route::get('{order_number}/online-betalen', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@orderPay')->name('cof.pay');

	Route::post('/webhook/cof-module-mollie', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@webhookMollie')->name('cof.mollie_webhook');
});
