<?php

use Chuckbe\ChuckcmsModuleOrderForm\Controllers\AccountController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\CategoryController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\CouponController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\CustomerController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\DiscountController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\LocationController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\OrderFormController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\POSController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\ProductController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\RewardController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\SettingsController;
use Chuckbe\ChuckcmsModuleOrderForm\Controllers\ShippingController;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'dashboard/order-form'], function () {
	
	Route::get('/', [OrderFormController::class, 'index'])->name('dashboard.module.order_form.index');
	
	//START OF: ORDERS ROUTES
	Route::get('/orders', [OrderController::class, 'index'])->name('dashboard.module.order_form.orders.index');
	Route::get('/orders/excel', [OrderController::class, 'excel'])->name('dashboard.module.order_form.orders.excel');
	Route::get('/orders/pdf', [OrderController::class, 'pdf'])->name('dashboard.module.order_form.orders.pdf');
	Route::get('/orders/{order}/detail', [OrderController::class, 'detail'])->name('dashboard.module.order_form.orders.detail');
	Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('dashboard.module.order_form.orders.invoice');
	Route::post('/orders/update_date', [OrderController::class, 'updateDate'])->name('dashboard.module.order_form.products.update_date');
	Route::post('/orders/update_address', [OrderController::class, 'updateAddress'])->name('dashboard.module.order_form.products.update_address');
	Route::post('/orders/resend_confirmation', [OrderController::class, 'resendConfirmation'])->name('dashboard.module.order_form.orders.resend_confirmation');
	//END OF:   ORDERS ROUTES
	
	//START OF: CATEGORIES ROUTES
	Route::get('/categories', [CategoryController::class, 'index'])->name('dashboard.module.order_form.categories.index');
	Route::get('/categories/{category}/sorting', [CategoryController::class, 'sorting'])->name('dashboard.module.order_form.categories.sorting');
	Route::post('/categories/{category}/sorting/update', [CategoryController::class, 'updateSort'])->name('dashboard.module.order_form.categories.update_sort');
	Route::post('/categories/save', [CategoryController::class, 'save'])->name('dashboard.module.order_form.categories.save');
	Route::post('/categories/delete', [CategoryController::class, 'delete'])->name('dashboard.module.order_form.categories.delete');
	//END OF:   CATEGORIES ROUTES
	
	//START OF: COUPONS ROUTES
	Route::get('/coupons', [CouponController::class, 'index'])->name('dashboard.module.order_form.coupons.index');
	Route::post('/coupons/save', [CouponController::class, 'save'])->name('dashboard.module.order_form.coupons.save');
	Route::post('/coupons/delete', [CouponController::class, 'delete'])->name('dashboard.module.order_form.coupons.delete');
	//END OF:   COUPONS ROUTES
	
	//START OF: DISCOUNTS ROUTES
	Route::get('/discounts', [DiscountController::class, 'index'])->name('dashboard.module.order_form.discounts.index');
	Route::get('/discounts/create', [DiscountController::class, 'create'])->name('dashboard.module.order_form.discounts.create');
	Route::get('/discounts/{discount}/edit', [DiscountController::class, 'edit'])->name('dashboard.module.order_form.discounts.edit');
	Route::post('/discounts/save', [DiscountController::class, 'save'])->name('dashboard.module.order_form.discounts.save');
	Route::post('/discounts/delete', [DiscountController::class, 'delete'])->name('dashboard.module.order_form.discounts.delete');
	Route::post('/discounts/refresh/code', [DiscountController::class, 'refreshCode'])->name('dashboard.module.order_form.discounts.refresh_code');
	//END OF: DISCOUNTS ROUTES
	
	//START OF: PRODUCTS ROUTES
	Route::get('/products', [ProductController::class, 'index'])->name('dashboard.module.order_form.products.index');
	Route::get('/products/create', [ProductController::class, 'create'])->name('dashboard.module.order_form.products.create');
	Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('dashboard.module.order_form.products.edit');
	Route::post('/products/save', [ProductController::class, 'save'])->name('dashboard.module.order_form.products.save');
	Route::post('/products/delete', [ProductController::class, 'delete'])->name('dashboard.module.order_form.products.delete');
	Route::post('/products/update', [ProductController::class, 'update'])->name('dashboard.module.order_form.products.update');
	//END OF: PRODUCTS ROUTES
	
	//START OF: PRODUCTS ROUTES
	Route::get('/customers', [CustomerController::class, 'index'])->name('dashboard.module.order_form.customers.index');
	Route::get('/customers/{customer}', [CustomerController::class, 'detail'])->name('dashboard.module.order_form.customers.detail');
	//END OF:   PRODUCTS ROUTES
	
	//START OF: REWARDS ROUTES
	Route::get('/rewards', [RewardController::class, 'index'])->name('dashboard.module.order_form.rewards.index');
	Route::post('/rewards/save', [RewardController::class, 'save'])->name('dashboard.module.order_form.rewards.save');
	Route::post('/rewards/delete', [RewardController::class, 'delete'])->name('dashboard.module.order_form.rewards.delete');
	//END OF:   REWARDS ROUTES

	//START OF: LOCATIONS ROUTES
	Route::get('/locations', [LocationController::class, 'index'])->name('dashboard.module.order_form.locations.index');
	Route::post('/locations/save', [LocationController::class, 'save'])->name('dashboard.module.order_form.locations.save');
	Route::post('/locations/delete', [LocationController::class, 'delete'])->name('dashboard.module.order_form.locations.delete');
	//END OF:   LOCATIONS ROUTES
	
	//START OF: SETTINGS ROUTES
	Route::get('/settings', [SettingsController::class, 'index'])->name('dashboard.module.order_form.settings.index');
	Route::post('/settings', [SettingsController::class, 'update'])->name('dashboard.module.order_form.settings.update');

	//START OF: POS ROUTES
	Route::get('/pos', [POSController::class, 'index'])->name('dashboard.module.order_form.pos.index');
	Route::get('/pos/data', [POSController::class, 'list'])->name('dashboard.module.order_form.pos.list');
	Route::post('/pos/place-order', [POSController::class, 'postOrder'])->name('dashboard.module.order_form.pos.place_order');

});

Route::group(['middleware' => ['web', 'auth', 'role:customer'], 'prefix' => 'mijn-account'], function () {
	Route::get('/', [AccountController::class, 'index'])->name('dashboard.module.order_form.account.index');
	Route::get('/coupons', [AccountController::class, 'coupons'])->name('dashboard.module.order_form.account.coupons');
	Route::get('/punten-inruilen', [AccountController::class, 'swapPoints'])->name('dashboard.module.order_form.account.swap_points');

	Route::post('/punten-inruilen/swap', [RewardController::class, 'swap'])->name('swap.points');
});


Route::group(['middleware' => ['web']], function() {
	Route::get('/product/{id}/json', [ProductController::class, 'json'])->name('cof.product.json');

	Route::post('/cof/place-order', [OrderController::class, 'postOrder'])->name('cof.place_order');
	Route::post('/cof/get-status', [OrderController::class, 'orderStatus'])->name('cof.status');
	Route::post('/cof/is-address-eligible', [ShippingController::class, 'isAddressEligible'])->name('cof.is_address_eligible');
	Route::post('/cof/is-valid-discount', [OrderController::class, 'checkDiscountCode'])->name('cof.check_discount_code');

	Route::get('{order_number}/bedankt-voor-uw-bestelling', [OrderController::class, 'orderFollowup'])->name('cof.followup');
	Route::get('{order_number}/online-betalen', [OrderController::class, 'orderPay'])->name('cof.pay');

	Route::post('/webhook/cof-module-mollie', [OrderController::class, 'webhookMollie'])->name('cof.mollie_webhook');
});
