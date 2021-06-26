<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('tenant', function () {
            return new \App\Services\TenantService;
        });
        App::bind('paramserv', function () {
            return new \App\Services\ParamService;
        });
        App::bind('user', function () {
            return new \App\Services\UserService;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
