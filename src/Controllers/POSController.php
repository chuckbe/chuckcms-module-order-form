<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\SettingsRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CategoryRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CustomerRepository;

use Chuckbe\Chuckcms\Models\FormEntry;
use ChuckSite;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Module;

class POSController extends Controller
{
    private $settingsRepository;
    private $productRepository;
    private $categoryRepository;
    private $locationRepository;
    private $customerRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingsRepository $settingsRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository, LocationRepository $locationRepository, CustomerRepository $customerRepository)
    {
        $this->settingsRepository = $settingsRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->locationRepository = $locationRepository;
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        $module = Module::where('slug', 'chuckcms-module-order-form')->first();
        $settings = $module->json['admin']['settings'];

        $products = $this->productRepository->get();
        $categories = $this->categoryRepository->get();
        $locations = $this->locationRepository->getForUser(\Auth::user()->id);
        $customers = $this->customerRepository->get();

        $locationIds = $locations->pluck('id')->flatten()->toArray();
        $now = now();
        $orders = FormEntry::where('slug', config('chuckcms-module-order-form.products.slug'))
            ->orderByDesc('created_at')
            ->whereIn('entry->location', $locationIds)
            ->where('entry->order_date', $now->format('d').'/'.$now->format('m').'/'.$now->format('Y'))
            ->where('entry->type', 'pos')
            ->get();

        $guest = $customers->where('email', 'guest@guest.com')->first();

        return view('chuckcms-module-order-form::pos.index')->with(compact('settings','products','categories','locations','customers','guest','orders'));
    }

    public function list()
    {
        $products = $this->productRepository->get();
        $collections = $this->categoryRepository->get();
        $locations = $this->locationRepository->getForUser(\Auth::user()->id);
        $customers = $this->customerRepository->get();
        return response()->json(['products' => $products,'collections' => $collections,'locations' => $locations,'customers' => $customers] );
    }

    public function update(Request $request)
    {

        $this->validate(request(), [
            "form.display_images" => "required|in:0,1",
            "form.display_description" => "required|in:0,1",
            "form.page" => "required|string",
            "cart.use_ui" => "required|in:0,1",
            "order.has_minimum_order_price" => "required|in:0,1",
            "order.minimum_order_price" => "required|numeric",
            "order.legal_text" => "required|string",
            "order.promo_check" => "required|in:0,1",
            "order.promo_text" => "required|string",
            "order.payment_upfront" => "required|in:0,1",
            "order.payment_description" => "required|string",
            "order.redirect_url" => "required|string",
            "emails.send_confirmation" => "required|in:0,1",
            "emails.confirmation_subject" => "required|string",
            "emails.send_notification" => "required|in:0,1",
            "emails.from_email" => "required|email",
            "emails.from_name" => "required|string",
            "emails.to_email" => "required|email",
            "emails.to_name" => "required|string",
            "emails.to_cc" => "nullable",
            "emails.to_bcc" => "nullable",
            "delivery.same_day" => "required|in:0,1",
            "delivery.same_day_until_hour" => "required|numeric|between:1,24",
            "delivery.next_day" => "required|in:0,1",
            "delivery.next_day_until_hour" => "required|numeric|between:1,24",
            "delivery.google_maps_api_key" => "nullable"
        ]);
    
        $settings = $this->settingsRepository->update($request);

        return redirect()->route('dashboard.module.order_form.settings.index');
        
    }

    /**
     * Store the order in the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function postOrder(Request $request)
    {
        $this->validate(request(), [
            'location' => 'required',
            'location_type' => 'required',
            'customer_id' => 'required',
            'products' => 'required',
            'coupons' => 'nullable',
            'discounts' => 'nullable',
            'subtotal' => 'required',
            'discount' => 'required',
            'total' => 'required',
            'vat' => 'required'
        ]);

        $order = new FormEntry();
        $order->slug = config('chuckcms-module-order-form.products.slug');
        
        $all_json = [];

        
        $all_json['order_number'] = str_random(8);
        $all_json['status'] = 'paid';
        $all_json['type'] = 'pos';

        $customer = $this->customerRepository->find($request['customer_id']);
        $all_json['first_name'] = $customer->surname;
        $all_json['last_name'] = $customer->name;
        $all_json['email'] = $customer->email;
        
        $all_json['tel'] = $request['tel'];
        $all_json['street'] = $request['street'];
        $all_json['housenumber'] = $request['housenumber'];
        $all_json['postalcode'] = $request['postalcode'];
        $all_json['city'] = $request['city'];
        $all_json['remarks'] = $request['remarks'];

        $all_json['client'] = $request['customer_id'];

        $all_json['location'] = $request['location'];
        $all_json['location_type'] = $request['location_type'];
        $all_json['order_date'] = $request['order_date'];
        $all_json['order_time'] = $request['order_time'];
        $all_json['order_subtotal'] = round($request['subtotal'], 2);
        $all_json['order_discount'] = round($request['discount'], 2);
        $all_json['order_price'] = round($request['total'], 2);
        $all_json['order_vat'] = round($request['vat'], 2);
        $all_json['order_price_no_vat'] = round($request['total'], 2) - round($request['vat'], 2);
        $all_json['order_shipping'] = round($request['shipping'], 2);
        $all_json['order_price_with_shipping'] = round(($request['total'] + $request['shipping']), 2);

        // if($request['total'] < ChuckSite::module('chuckcms-module-order-form')->getSetting('order.minimum_order_price')) {
        //     return response()->json([
        //         'status' => 'error'
        //     ]);
        // }
        // 
        

        // $all_json['order_number'] = str_random(8);
        // $all_json['status'] = 'awaiting';
        // $all_json['type'] = 'web';
        // $all_json['first_name'] = $request['surname'];
        // $all_json['last_name'] = $request['name'];
        // $all_json['email'] = $request['email'];
        // $all_json['tel'] = $request['tel'];
        // $all_json['street'] = $request['street'];
        // $all_json['housenumber'] = $request['housenumber'];
        // $all_json['postalcode'] = $request['postalcode'];
        // $all_json['city'] = $request['city'];
        // $all_json['remarks'] = $request['remarks'];

        // $all_json['location'] = $request['location'];
        // $all_json['order_date'] = $request['order_date'];
        // $all_json['order_time'] = $request['order_time'];
        // $all_json['order_price'] = round($request['total'], 2);
        // $all_json['order_shipping'] = round($request['shipping'], 2);
        // $all_json['order_price_with_shipping'] = round(($request['total'] + $request['shipping']), 2);

        // if($request['total'] < ChuckSite::module('chuckcms-module-order-form')->getSetting('order.minimum_order_price')) {
        //     return response()->json([
        //         'status' => 'error'
        //     ]);
        // }





        
        $items = [];

        foreach($request['products'] as $product){
            $item = [];
            $prodKey = $product['id'];
            
            $item['id'] = $prodKey;
            $item['name'] = $product['name'];
            $item['price'] = $product['current_price'];
            $item['qty'] = $product['quantity'];
            $item['totprice'] = round($product['total_price'], 2);

            if($product['attribute'] == ''){
                $item['attributes'] = false;
            } else {
                $item['attributes'] = $product['attribute'];
            }

            if(array_key_exists('options', $product) && $product['options'] !== []){
                $item['options'] = $product['options'];
            } else {
                $item['options'] = false;
            }

            if(array_key_exists('extras', $product) && $product['extras'] !== []){
                $item_extras = $product['extras'];
                $extras = [];
                if($item_extras !== false) {
                    foreach($item_extras as $item_extra) {
                        if(count((array)$item_extra) > 0) {
                            $extras[] = $item_extra;
                        }
                    }
                }

                if(count($extras) > 0) {
                    $item['extras'] = $extras;
                } else {
                    $item['extras'] = false;
                }
            } else {
                $item['extras'] = false;
            }

            if(array_key_exists('subproducts', $product) && $product['subproducts'] !== []){
                $item_subproducts = $product['subproducts'];
                $subproducts = [];
                if($item_subproducts !== false) {
                    foreach($item_subproducts as $item_subproduct) {
                        if(count((array)$item_subproduct) > 0) {
                            $subproducts[] = $item_subproduct;
                        }
                    }
                }

                if(count($subproducts) > 0) {
                    $item['subproducts'] = $subproducts;
                } else {
                    $item['subproducts'] = false;
                }
            } else {
                $item['subproducts'] = false;
            }

            if(array_key_exists('discounts', $product)) {
                $item['discounts'] = $product['discounts'];
            }

            if(array_key_exists('discount', $product)) {
                $item['discount'] = $product['discount'];
            }

            $items[] = $item;
            
        }


        $all_json['items'] = $items;

        $discounts = [];
        if (is_array($request['discounts']) && count($request['discounts']) > 0) {
            foreach($request['discounts'] as $singleDiscount){
                $discount = [];
                $discount['id'] = $singleDiscount['id'];

                $discounts[] = $discount;
            }
        }

        $all_json['discounts'] = $discounts;

        $coupons = [];
        if (is_array($request['coupons']) && count($request['coupons']) > 0) {
            foreach($request['coupons'] as $singleCoupon){
                $coupon = [];
                $coupon['id'] = $singleCoupon['id'];

                $coupons[] = $coupon;
            }
        }

        $all_json['coupons'] = $coupons;

        $order->entry = $all_json;

        if($order->save()){
            if (!$customer->guest) {
                $customer->incrementLoyaltyPoints(floor(round($request['total'], 2)));
                $customer->useCoupons($request['coupons']);
            }

            $locations = $this->locationRepository->getForUser(\Auth::user()->id);
            $locationIds = $locations->pluck('id')->flatten()->toArray();
            $now = now();
            $ordersCount = FormEntry::where('slug', config('chuckcms-module-order-form.products.slug'))
                ->orderByDesc('created_at')
                ->whereIn('entry->location', $locationIds)
                ->where('entry->order_date', $now->format('d').'/'.$now->format('m').'/'.$now->format('Y'))
                ->where('entry->type', 'pos')
                ->count();

            $orderLine = view('chuckcms-module-order-form::pos.includes.order_table_line')
                ->with(compact('ordersCount','order'))->render();

            return response()->json([
                'status' => 'success',
                'order_number' => $all_json['order_number'],
                'url' => route('cof.followup', ['order_number' => $order->entry['order_number']]),
                'order_table_line' => $orderLine
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
