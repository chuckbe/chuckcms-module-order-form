<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Module;

class SettingsController extends Controller
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
        $module = Module::where('slug', 'chuckcms-module-order-form')->first();
        $settings = $module->json['admin']['settings'];

        return view('chuckcms-module-order-form::backend.settings.index')->with(compact('settings'));
    }
}
