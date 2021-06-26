<?php

namespace App\Facades;

class UserService extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'user';
    }
}
