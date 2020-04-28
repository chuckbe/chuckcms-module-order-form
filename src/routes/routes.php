<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
		
		Route::get('/dashboard/order-form', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderFormController@index')->name('dashboard.module.order_form.index');
		
		//START OF: ORDERS ROUTES
		Route::get('/dashboard/order-form/orders', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@index')->name('dashboard.module.order_form.orders.index');
		Route::get('/dashboard/order-form/orders/{order}/detail', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@detail')->name('dashboard.module.order_form.orders.detail');
		Route::post('/dashboard/order-form/orders/update_date', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@updateDate')->name('dashboard.module.order_form.products.update_date');
		//END OF: ORDERS ROUTES
		
		//START OF: PRODUCTS ROUTES
		Route::get('/dashboard/order-form/products', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@index')->name('dashboard.module.order_form.products.index');
		Route::get('/dashboard/order-form/products/create', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@create')->name('dashboard.module.order_form.products.create');
		Route::get('/dashboard/order-form/products/{product}/edit', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@edit')->name('dashboard.module.order_form.products.edit');
		Route::post('/dashboard/order-form/products/save', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@save')->name('dashboard.module.order_form.products.save');
		Route::post('/dashboard/order-form/products/delete', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@delete')->name('dashboard.module.order_form.products.delete');
		Route::post('/dashboard/order-form/products/update', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController@update')->name('dashboard.module.order_form.products.update');
		//END OF: PRODUCTS ROUTES
		
		//START OF: SETTINGS ROUTES
		Route::get('/dashboard/order-form/settings', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\SettingsController@index')->name('dashboard.module.order_form.settings.index');
	});

	Route::post('/cof/place-order', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@postOrder')->name('cof.place_order');
	Route::post('/cof/get-status', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@orderStatus')->name('cof.status');
	Route::post('/cof/is-address-eligible', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\ShippingController@isAddressEligible')->name('cof.is_address_eligible');

	Route::get('{order_number}/bedankt-voor-uw-bestelling', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@orderFollowup')->name('cof.followup');
	Route::get('{order_number}/online-betalen', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@orderPay')->name('cof.pay');

	Route::post('/webhook/cof-module-mollie', 'Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController@webhookMollie')->name('cof.mollie_webhook');
});
