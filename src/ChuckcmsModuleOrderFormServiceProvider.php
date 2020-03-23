<?php

namespace Chuckbe\ChuckcmsModuleOrderForm;

use Chuckbe\ChuckcmsModuleOrderForm\Commands\InstallModuleOrderForm;
use Illuminate\Support\ServiceProvider;

class ChuckcmsModuleOrderFormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        
        //php artisan vendor:publish --tag=chuckcms-module-order-form-public --force
        $this->publishes([
            __DIR__.'/../assets' => public_path('chuckbe/chuckcms-module-order-form'),
        ], 'chuckcms-module-order-form-public');

        $this->publishes([
            __DIR__ . '/../config/chuckcms-module-order-form.php' => config_path('chuckcms-module-order-form.php'),
        ], 'chuckcms-module-order-form-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallModuleOrderForm::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {   
        $this->loadViewsFrom(__DIR__.'/views', 'chuckcms-module-order-form');

        $this->app->register(
            'Chuckbe\ChuckcmsModuleOrderForm\Providers\ChuckModuleOrderFormServiceProvider'
        );

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ChuckModuleOrderForm', 'Chuckbe\ChuckcmsModuleOrderForm\Facades\ChuckModuleOrderForm');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/chuckcms-module-order-form.php', 'chuckcms-module-order-form'
        );
    }
}
