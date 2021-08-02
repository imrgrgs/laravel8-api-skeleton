<?php

namespace App\Facades;

class RoleService extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'role';
    }
}
