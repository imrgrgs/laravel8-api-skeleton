<?php

namespace App\Services\Generator;

use Carbon\Carbon;

class HashCode
{
    public static function make($prefix = '036329', $more_entropy = true)
    {
        return Carbon::now()->format('Y-m-d') .  '.' . uniqid($prefix, $more_entropy);
    }
}
