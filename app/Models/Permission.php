<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;
use Laratrust\Models\LaratrustPermission;
use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends LaratrustPermission
{
    use HasTranslations;
    public $translatable = ['display_name', 'description'];

    use LogsActivity;
    /**
     * All fillable attributes will be logged
     *
     * @var boolean
     */
    static $logFillable = true;


    /**
     * to log every attribute in your $logAttributes variable,
     * but only those that has actually changed after the update
     *
     * @var boolean
     */
    protected static $logOnlyDirty = true;

    /**
     * prevents the package from storing empty logs
     *
     * @var boolean
     */
    protected static $submitEmptyLogs = false;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A " . $this->table . " record has been {$eventName}";
    }

    public function getLogNameToUse(string $eventName)
    {
        return $this->table;
    }
    /**
     * all attributes are fillable
     */
    public $guarded = [];
}
