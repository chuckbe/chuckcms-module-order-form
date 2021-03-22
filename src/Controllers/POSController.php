<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\SettingsRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CategoryRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\LocationRepository;

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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingsRepository $settingsRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository, LocationRepository $locationRepository)
    {
        $this->settingsRepository = $settingsRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->locationRepository = $locationRepository;
    }

    public function index()
    {
        $module = Module::where('slug', 'chuckcms-module-order-form')->first();
        $settings = $module->json['admin']['settings'];

        $products = $this->productRepository->get();
        $categories = $this->categoryRepository->get();
        $locations = $this->locationRepository->getForUser(\Auth::user()->id);

        return view('chuckcms-module-order-form::pos.index')->with(compact('settings','products','categories','locations'));
    }

    public function list()
    {
        $products = $this->productRepository->get();
        $collections = $this->categoryRepository->get();
        $locations = $this->locationRepository->getForUser(\Auth::user()->id);
        return response()->json(['products' => $products,'collections' => $collections,'locations' => $locations] );
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
}
