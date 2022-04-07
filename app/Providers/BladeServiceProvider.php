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
       
        Blade::if('scope', function($name){
            if (Cache::has('scopes_'.auth()->user()->store->id)) {
                $scopes=Cache::get('scopes_'.auth()->user()->store->id);
            } else {
               $scopes=auth()->user()->store->scope->pluck('name');
            }
            if (!$name) {
                return true;
            }
            if (auth()->user()) {
                return $scopes->contains($name);
            }
            return false;
        });
    }
}
