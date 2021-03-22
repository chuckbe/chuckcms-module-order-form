<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\FormEntry;

class OrderFormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        if (ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront')) {
            $orders = FormEntry::where('slug', config('chuckcms-module-order-form.products.slug'))
                                    ->where('entry->status', 'paid')
                                    ->get();
        } else {
            $orders = FormEntry::where('slug', config('chuckcms-module-order-form.products.slug'))
                                    ->where('entry->status', 'awaiting')
                                    ->get();
        }
        
        return view('chuckcms-module-order-form::backend.dashboard.index', compact('orders'));
    }
}
