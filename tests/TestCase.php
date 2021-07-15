<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Chuckbe\ChuckcmsModuleOrderForm\ChuckcmsModuleOrderFormServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return[
            ChuckcmsModuleOrderFormServiceProvider::class,
        ];
    }
}
