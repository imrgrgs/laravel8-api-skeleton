<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;


class ParamValue extends ModelBase
{

    use HasTranslations;

    /**
     * Defines wich attributes are translatable
     *
     * @var array
     */

    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'param_id',
        'code',
        'name',
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

      /**
       * Undocumented function
       *
       * @return BelongsTo
       */
    public function param() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Param::class, 'param_id', 'id');
    }

    /**
     * Undocumented function
     *
     * @return HasOne
     */
    public function description() : HasOne
    {
        return $this->hasOne(\App\Models\ParamValueDescription::class, 'param_value_id', 'id');
    }
}
