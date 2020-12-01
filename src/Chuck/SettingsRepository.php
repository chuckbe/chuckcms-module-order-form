<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use ChuckSite;
use Illuminate\Http\Request;
use Chuckbe\Chuckcms\Models\Module;

class SettingsRepository
{
    public function __construct()
    {

    }

    /**
     *  Get all the settings
     *  @var string
     */
    public function get()
    {

    }

    public function update(Request $values)
    {
        $module = Module::where('slug', 'chuckcms-module-order-form')->firstOrFail();
        $settings = $values->except("_token", "settings_id", "settings_save");
        $json = $module->json;
        $json['admin']['settings'] = $settings;
        $module->json = $json;
        $module->update();
        return $module;

    }
    
}