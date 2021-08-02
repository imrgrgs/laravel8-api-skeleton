<?php

namespace App\Providers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('permission', function () {
            return new \App\Services\PermissionService;
        });
        App::bind('role', function () {
            return new \App\Services\RoleService;
        });

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
