<?php

namespace App\Facades;

class TenantService extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tenant';
    }
}
