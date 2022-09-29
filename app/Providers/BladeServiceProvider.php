<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
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

        Blade::if('scope', function ($value) {
           
            if(!$value){
                return true;
            }
            $value=config('scopes.'.$value);
            return $value;
           
        });
        Blade::if('scopeanny', function (array $scopesArray) {
           foreach($scopesArray as $scope){
               
                   return $scope;
           }
        });
    }
}
