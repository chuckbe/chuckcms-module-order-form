<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\SettingsRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Module;

class SettingsController extends Controller
{
    private $settingsRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function index()
    {
        $module = Module::where('slug', 'chuckcms-module-order-form')->first();
        $settings = $module->json['admin']['settings'];

        return view('chuckcms-module-order-form::backend.settings.index')->with(compact('settings'));
    }

    public function update(Request $request)
    {

        $this->validate(request(), [
            "categories" => "required|array",
            "form" => "required|array|min:3",
            "cart" => "required|array|max:1",
            "order" => "required|array|min:8",
            "emails" => "required|array|min:10",
            "locations" => "required|array|min:2",
            "locations.afhalen" => "required|array|min:12",
            "locations.leveren" => "required|array|min:12",
            "delivery" => "required|array|min:5",
            "categories.*.name" => "required|string",
            "categories.*.is_displayed" => "required|in:1,0",
            "form.display_images" => "required|in:true,false",
            "form.page" => "required|string",
            "cart.use_ui" => "required|in:true,false",
            "order.has_minimum_order_price" => "required|in:true,false",
            "order.minimum_order_price" => "required",
            "order.legal_text" => "required|string",
            "order.promo_check" => "required|in:true,false",
            "order.promo_text" => "required|string",
            "order.payment_upfront" => "required|in:true,false",
            "order.payment_description" => "required|string",
            "order.redirect_url" => "required|string",
            "emails.send_confirmation" => "required|in:true,false",
            "emails.confirmation_subject" => "required|string",
            "emails.send_notification" => "required|in:true,false",
            "emails.from_email" => "required|string",
            "emails.from_name" => "required|string",
            "emails.to_email" => "required|string",
            "emails.to_name" => "required|string",
            "emails.to_cc" => "required|in:true,false",
            "emails.to_bcc" => "required|in:true,false",
            "locations.afhalen.type" => "required|string",
            "locations.afhalen.name" => "required|string",
        ]);
    
        $settings = $this->settingsRepository->update($request);

        return redirect()->route('dashboard.module.order_form.settings.index');
        
    }
}
