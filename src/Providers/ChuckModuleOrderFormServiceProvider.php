<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Providers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\OrderFormRepository;

use Exception;
use Illuminate\Support\ServiceProvider;

class ChuckModuleOrderFormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void 
     */
    public function register()
    {
        $this->app->singleton('ChuckModuleOrderForm',function(){
            return new \Chuckbe\ChuckcmsModuleOrderForm\Chuck\Accessors\ChuckModuleOrderForm(\App::make(OrderFormRepository::class), \App::make(ProductRepository::class));
        });
    }
}