<?php

namespace App\Models;


use Spatie\Translatable\HasTranslations;


class Param extends ModelBase
{

    use HasTranslations;

    /**
     * Defines wich attributes are translatable
     *
     * @var array
     */

    public $translatable = ['display_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
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
      |-------------------------------------------------------*/

    /**
     * Return description of Param
     *
     * @return \App\Models\ParamDescription|null
     */
    public function description()
    {
        return $this->hasOne(\App\Models\ParamDescription::class, 'param_id', 'id');
    }

    /**
     * Return a collection of \App\Models\ParamValue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     */
    public function values()
    {
        return $this->hasMany(\App\Models\ParamValue::class, 'param_id', 'id');
    }
}
