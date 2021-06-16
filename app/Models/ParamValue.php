<?php

namespace App\Models;


use Spatie\Translatable\HasTranslations;


class ParamValue extends ModelBase
{

    use HasTranslations;

    /**
     * Defines wich attributes are translatable
     *
     * @var array
     */

    public $translatable = ['label'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'param_id',
        'code',
        'label',
        'symbol',
        'color',
        'is_visible',
        'is_default',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_visible' => 'boolean',
        'is_default' => 'boolean',

    ];


    /*|-----------------------------------------------------------------------
      | Appends = calculated attributes
      |-----------------------------------------------------------------------
      */

    protected $appends = [
        //
    ];

    /*|-------------------------------------------------------
      | Relationships
      |-------------------------------------------------------
      */

    public function param()
    {
        return $this->belongsTo(\App\Models\Param::class, 'param_id', 'id');
    }

    public function description()
    {
        return $this->hasOne(\App\Models\ParamValueDescription::class, 'param_value_id', 'id');
    }
}
