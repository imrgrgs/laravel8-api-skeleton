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
