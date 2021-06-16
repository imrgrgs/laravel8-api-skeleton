<?php

namespace App\Models;


use Spatie\Translatable\HasTranslations;


class ParamValueDescription extends ModelBase
{

    use HasTranslations;

    /**
     * Defines wich attributes are translatable
     *
     * @var array
     */
    public $translatable = ['description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'param_value_id',
        'description',
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
        //
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
    /**
     * Return ParamValue object
     *
     * @return \App\Models\ParamValue|null
     */
    public function paramValue()
    {
        return $this->belongsTo(\App\Models\ParamValue::class, 'param_value_id', 'id');
    }
}
