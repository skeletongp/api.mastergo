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

        Blade::if('scope', function ($name) {
            return true;
           /*  if (!$name) {
                return true;
            }
            $scopes = explode(',', env('STORE_SCOPES'));
            return in_array($name, $scopes); */
        });
        Blade::if('scopeanny', function (array $scopesArray) {
            return true;
            if (Cache::has('scopes_' . auth()->user()->store->id)) {
                $scopes = Cache::get('scopes_' . auth()->user()->store->id);
            } else {
                $scopes = auth()->user()->store->scope()->pluck('name');
                Cache::put('scopes_' . auth()->user()->store->id, $scopes);
            }
            if (!$scopesArray || count($scopesArray) == 0) {
                return true;
            }
            if (auth()->user()) {
                foreach ($scopesArray as $scope) {
                    if ($scopes->contains($scope)) {
                        return true;
                    }
                }
            }
            return false;
        });
    }
}
