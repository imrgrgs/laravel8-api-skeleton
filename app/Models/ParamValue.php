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
