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
        'code',
        'is_master',
        'is_active',
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
        'is_master' => 'boolean',
        'is_active' => 'boolean',
    ];


    /*|-----------------------------------------------------------------------
      | Appends = calculated attributes
      |-----------------------------------------------------------------------
      */

    protected $appends = [
        //
    ];
    /*|-----------------------------------------------------------------------
      | END Appends = calculated attributes
      |-----------------------------------------------------------------------
      */


    /*|-------------------------------------------------------
      | Relationships
      |-------------------------------------------------------*/


    /**
     * Return a collection of \App\Models\User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'tenant_id', 'id');
    }
    /*|-----------------------------------------------------------------------
      | END Relationships
      |-----------------------------------------------------------------------
      */
}
