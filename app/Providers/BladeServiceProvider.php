<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('scope', function($name){
            if (!$name) {
                return true;
            }
            if (auth()->user()) {
                return auth()->user()->store->scope()->where('name', $name)->first();
            }
            return false;
        });
    }
}
