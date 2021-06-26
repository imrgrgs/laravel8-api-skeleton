<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'param_id',
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
       * Undocumented function
       *
       * @return BelongsTo
       */
    public function param() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Param::class, 'param_id', 'id');
    }
}
