<?php

namespace App\Traits;

trait ApiLogsActivity
{
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
}
