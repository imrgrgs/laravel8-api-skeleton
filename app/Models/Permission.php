<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;
use Laratrust\Models\LaratrustPermission;
use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends LaratrustPermission
{
    use HasTranslations;
    use LogsActivity;
    public $translatable = ['display_name', 'description'];
    public $guarded = [];
}
