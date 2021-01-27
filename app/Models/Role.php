<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends LaratrustRole
{
    use HasTranslations;
    use LogsActivity;
    public $translatable = ['display_name', 'description'];
    public $guarded = [];
}
