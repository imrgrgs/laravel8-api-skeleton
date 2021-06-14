<?php

namespace App\Models;


use Spatie\Translatable\HasTranslations;


class ParamDescription extends ModelBase
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
      public function param()
      {
          return $this->belongsTo(\App\Models\Param::class, 'param_id', 'id');
      }

}
