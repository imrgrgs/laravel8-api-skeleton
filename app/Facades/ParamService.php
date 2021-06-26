<?php

namespace App\Facades;

class ParamService extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'paramserv';
    }
}
