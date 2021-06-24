<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends ModelBase
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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

}
