<?php

namespace App\Models;


use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    const AVATAR_STORAGE = 'public/images/avatars';
    const AVATAR_THUMB_STORAGE = 'public/images/avatars/thumb';
    const AVATAR_DEFAULT = 'default-avatar.png';

    use LaratrustUserTrait;
    use HasFactory, Notifiable;

    use LogsActivity;
    use SoftDeletes;
    /**
     * All fillable attributes will be logged
     *
     * @var boolean
     */
    static $logFillable = true;


    /**
     * to log every attribute in your $logAttributes variable,
     * but only those that has actually changed after the update
     *
     * @var boolean
     */
    protected static $logOnlyDirty = true;

    /**
     * prevents the package from storing empty logs
     *
     * @var boolean
     */
    protected static $submitEmptyLogs = false;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A " . $this->table . " record has been {$eventName}";
    }

    public function getLogNameToUse(string $eventName)
    {
        return $this->table;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'active',
        'avatar',
        'module',
        'provider', // social login
        'provider_id', // social login
        'provider_response' // social login
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider', // social login
        'provider_id', // social login
        'provider_response' // social login

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];


    /*|-----------------------------------------------------------------------
      | Appends = calculated attributes
      |-----------------------------------------------------------------------
      */

    protected $appends = ['_link',];

    /**
     * @return User link to show
     **/
    public function getLinkAttribute()
    {
        if ($this->avatar) {
            $href = URL::to('/') . Storage::url(self::AVATAR_STORAGE) . '/' . $this->avatar;
        } else {
            $href = URL::to('/') . Storage::url(self::AVATAR_STORAGE) . '/' . self::AVATAR_DEFAULT;
        }
        return [
            'href_avatar' => $href,

        ];
    }

    /*|-----------------------------------------------------------------------
      | END Appends = calculated attributes
      |-----------------------------------------------------------------------
      */

    /*|-------------------------------------------------------
      | Relationships
      |-------------------------------------------------------
      */

    /**
     * Undocumented function
     *
     * @return void
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id', 'id');
    }

    /*|-----------------------------------------------------------------------
      | END Relationships
      |-----------------------------------------------------------------------
      */

    /*|----------------------------------------------------------------------------
      | Json Web Token for API access
      | It is part of tymon/jwt-auth package
      |----------------------------------------------------------------------------
      */

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    /*|----------------------------------------------------------------------------
      | END Json Web Token for API access
      |----------------------------------------------------------------------------
      */
}
