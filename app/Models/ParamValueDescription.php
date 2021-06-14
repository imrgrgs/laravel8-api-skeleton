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
