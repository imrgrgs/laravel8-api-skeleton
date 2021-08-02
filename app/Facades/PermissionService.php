<?php

namespace App\Facades;

class PermissionService extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'permission';
    }
}
