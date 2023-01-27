<?php

namespace Chuckbe\ChuckcmsModuleOrderForm;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class ChuckcmsModuleOrderFormServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->doPublishing();

        $this->registerCommands();

        //$this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'chuckcms-module-order-form');
        
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'chuckcms-module-order-form');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/order-form.php',
            'chuckcms-module-order-form'
        );

        $this->app->register(
            'Chuckbe\ChuckcmsModuleOrderForm\Providers\ChuckModuleOrderFormServiceProvider'
        );

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ChuckModuleOrderForm', 'Chuckbe\ChuckcmsModuleOrderForm\Facades\ChuckModuleOrderForm');
    }

    protected function doPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/order-form.php' => config_path('chuckcms-module-order-form.php'),
        ], 'order-form-config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_coupons_tables.php.stub' => $this->getMigrationFileName('create_coupons_tables.php'),
            __DIR__.'/../database/migrations/create_customers_tables.php.stub' => $this->getMigrationFileName('create_customers_tables.php'),
        ], 'order-form-migrations');

        //php artisan vendor:publish --tag=order-form-views --force
        $this->publishes([
            __DIR__.'/../assets' => public_path('chuckbe/chuckcms-module-order-form'),
        ], 'order-form-assets');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/chuckcms-module-order-form'),
        ], 'order-form-views');
    }

    protected function registerCommands()
    {
        $this->commands([
            Commands\InstallModuleOrderForm::class,
        ]);
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
