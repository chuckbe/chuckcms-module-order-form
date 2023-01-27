<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck\Accessors;

use App\Http\Requests;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\OrderFormRepository;

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

use Exception;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class ChuckModuleOrderForm
{
    private $orderFormRepository;
    private $productRepository;

    public function __construct(
        OrderFormRepository $orderFormRepository, 
        ProductRepository $productRepository
    ) {
        $this->orderFormRepository = $orderFormRepository;
        $this->productRepository = $productRepository;
    }

    public function renderForm()
    {
        return $this->orderFormRepository->render();
    }

    public function renderScripts()
    {
        return $this->orderFormRepository->scripts();
    }

    public function renderStyles()
    {
        return $this->orderFormRepository->styles();
    }

    public function followupContent(string $order_number)
    {
        return $this->orderFormRepository->followup($order_number);
    }

    public function followupScripts(string $order_number)
    {
        return $this->orderFormRepository->followupScripts($order_number);
    }

    public function followupStyles(string $order_number)
    {
        return $this->orderFormRepository->followupStyles($order_number);
    }

    public function firstAvailableDate(string $locationKey)
    {
        return $this->orderFormRepository->firstAvailableDate($locationKey);
    }

    public function firstAvailableDateInDaysFromNow(string $locationKey)
    {
        return $this->orderFormRepository->firstAvailableDateInDaysFromNow($locationKey);
    }

    public function totalSales()
    {
        return $this->orderFormRepository->totalSales();
    }

    public function totalSalesLast7Days()
    {
        return $this->orderFormRepository->totalSalesLast7Days();
    }

    public function totalSalesLast7DaysQty()
    {
        return $this->orderFormRepository->totalSalesLast7DaysQty();
    }

    public function getSubproducts($subproducts = [])
    {
        if (!is_array($subproducts) || (is_array($subproducts) && count($subproducts) == 0)) {
            return json_encode([]);
        }

        $subproductIds = $this->getSubproductIds($subproducts);

        $products = $this->productRepository->whereIn($subproductIds);

        foreach($subproducts as $spKey => $subproductGroup) {
            foreach ($subproductGroup['products'] as $spgKey => $spgP) {
                $subproducts[$spKey]['products'][$spgKey]['product'] = $products->where('id', $spgP['id'])->first();
            }
        }

        return json_encode($subproducts);
    }

    private function getSubproductIds(array $subproducts)
    {
        $ids = [];
        foreach ($subproducts as $subproductGroup) {
            foreach ($subproductGroup['products'] as $spgP) {
                $ids[] = $spgP['id'];
            }
        }

        return array_unique($ids);
    }

    public static function routes()
    {
        Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'dashboard/order-form', 'as' => 'dashboard.module.order_form.'], function () {
    
            Route::get('/', [OrderFormController::class, 'index'])
                ->name('index');
            

            //START OF: ORDERS ROUTES
            Route::get('/orders', [OrderController::class, 'index'])
                ->name('orders.index');

            Route::get('/orders/excel', [OrderController::class, 'excel'])
                ->name('orders.excel');

            Route::get('/orders/pdf', [OrderController::class, 'pdf'])
                ->name('orders.pdf');

            Route::get('/orders/{order}/detail', [OrderController::class, 'detail'])
                ->name('orders.detail');

            Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])
                ->name('orders.invoice');

            Route::post('/orders/update_date', [OrderController::class, 'updateDate'])
                ->name('products.update_date');

            Route::post('/orders/update_address', [OrderController::class, 'updateAddress'])
                ->name('products.update_address');

            Route::post('/orders/resend_confirmation', [OrderController::class, 'resendConfirmation'])
                ->name('orders.resend_confirmation');
            //END OF:   ORDERS ROUTES
            

            //START OF: CATEGORIES ROUTES
            Route::get('/categories', [CategoryController::class, 'index'])
                ->name('categories.index');
            
            Route::get('/categories/{category}/sorting', [CategoryController::class, 'sorting'])
                ->name('categories.sorting');
            
            Route::post('/categories/{category}/sorting/update', [CategoryController::class, 'updateSort'])
                ->name('categories.update_sort');
            
            Route::post('/categories/save', [CategoryController::class, 'save'])
                ->name('categories.save');
            
            Route::post('/categories/delete', [CategoryController::class, 'delete'])
                ->name('categories.delete');
            //END OF:   CATEGORIES ROUTES
            

            //START OF: COUPONS ROUTES
            Route::get('/coupons', [CouponController::class, 'index'])
                ->name('coupons.index');

            Route::post('/coupons/save', [CouponController::class, 'save'])
                ->name('coupons.save');

            Route::post('/coupons/delete', [CouponController::class, 'delete'])
                ->name('coupons.delete');
            //END OF:   COUPONS ROUTES
            

            //START OF: DISCOUNTS ROUTES
            Route::get('/discounts', [DiscountController::class, 'index'])
                ->name('discounts.index');

            Route::get('/discounts/create', [DiscountController::class, 'create'])
                ->name('discounts.create');

            Route::get('/discounts/{discount}/edit', [DiscountController::class, 'edit'])
                ->name('discounts.edit');

            Route::post('/discounts/save', [DiscountController::class, 'save'])
                ->name('discounts.save');

            Route::post('/discounts/delete', [DiscountController::class, 'delete'])
                ->name('discounts.delete');

            Route::post('/discounts/refresh/code', [DiscountController::class, 'refreshCode'])
                ->name('discounts.refresh_code');
            //END OF: DISCOUNTS ROUTES
            

            //START OF: PRODUCTS ROUTES
            Route::get('/products', [ProductController::class, 'index'])
                ->name('products.index');

            Route::get('/products/create', [ProductController::class, 'create'])
                ->name('products.create');

            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
                ->name('products.edit');

            Route::post('/products/save', [ProductController::class, 'save'])
                ->name('products.save');

            Route::post('/products/delete', [ProductController::class, 'delete'])
                ->name('products.delete');

            Route::post('/products/update', [ProductController::class, 'update'])
                ->name('products.update');
            //END OF: PRODUCTS ROUTES
            

            //START OF: PRODUCTS ROUTES
            Route::get('/customers', [CustomerController::class, 'index'])
                ->name('customers.index');

            Route::get('/customers/{customer}', [CustomerController::class, 'detail'])
                ->name('customers.detail');
            //END OF:   PRODUCTS ROUTES
            

            //START OF: REWARDS ROUTES
            Route::get('/rewards', [RewardController::class, 'index'])
                ->name('rewards.index');

            Route::post('/rewards/save', [RewardController::class, 'save'])
                ->name('rewards.save');

            Route::post('/rewards/delete', [RewardController::class, 'delete'])
                ->name('rewards.delete');
            //END OF:   REWARDS ROUTES


            //START OF: LOCATIONS ROUTES
            Route::get('/locations', [LocationController::class, 'index'])
                ->name('locations.index');

            Route::post('/locations/save', [LocationController::class, 'save'])
                ->name('locations.save');

            Route::post('/locations/delete', [LocationController::class, 'delete'])
                ->name('locations.delete');
            //END OF:   LOCATIONS ROUTES
            

            //START OF: SETTINGS ROUTES
            Route::get('/settings', [SettingsController::class, 'index'])
                ->name('settings.index');

            Route::post('/settings', [SettingsController::class, 'update'])
                ->name('settings.update');
            //END OF:   SETTINGS ROUTES

            //START OF: POS ROUTES
            Route::get('/pos', [POSController::class, 'index'])
                ->name('pos.index');

            Route::get('/pos/data', [POSController::class, 'list'])
                ->name('pos.list');

            Route::post('/pos/place-order', [POSController::class, 'postOrder'])
                ->name('pos.place_order');
            //END OF:   POS ROUTES
        });
    }

    public static function frontend()
    {
        Route::group([
            'middleware' => ['web', 'auth', 'role:customer'], 
            'prefix' => 'mijn-account', 
            'as' => 'dashboard.module.order_form.'
        ], function () {
            Route::get('/', [AccountController::class, 'index'])
                ->name('account.index');

            Route::get('/coupons', [AccountController::class, 'coupons'])
                ->name('account.coupons');

            Route::get('/punten-inruilen', [AccountController::class, 'swapPoints'])
                ->name('account.swap_points');

            Route::post('/punten-inruilen/swap', [RewardController::class, 'swap'])
                ->name('swap.points');
        });


        Route::group(['middleware' => ['web']], function() {
            Route::get('/product/{id}/json', [ProductController::class, 'json'])
                ->name('cof.product.json');

            Route::post('/cof/place-order', [OrderController::class, 'postOrder'])
                ->name('cof.place_order');
            Route::post('/cof/get-status', [OrderController::class, 'orderStatus'])
                ->name('cof.status');
            Route::post('/cof/is-address-eligible', [ShippingController::class, 'isAddressEligible'])
                ->name('cof.is_address_eligible');
            Route::post('/cof/is-valid-discount', [OrderController::class, 'checkDiscountCode'])
                ->name('cof.check_discount_code');

            Route::get('{order_number}/bedankt-voor-uw-bestelling', [OrderController::class, 'orderFollowup'])
                ->name('cof.followup');
            Route::get('{order_number}/online-betalen', [OrderController::class, 'orderPay'])
                ->name('cof.pay');

            Route::post('/webhook/cof-module-mollie', [OrderController::class, 'webhookMollie'])
                ->name('cof.mollie_webhook');
        });
    }
}