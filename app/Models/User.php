<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable;
use Plank\Metable\Metable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;
    use Notifiable;
    use SoftDeletes;
    use Metable;

    protected $with = ['merchant', 'country', 'region'];
    protected $appends = ['researches_view', 'vehicles_view', 'alerts_view', 'products_view', 'matchs_view', 'alerts_match_view', 'account_activated'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    /**
     * Return a key value array, containing any custom claims to be added to
     * the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getResearchesViewAttribute()
    {
        return $this->getMeta('researches_view');
    }

    public function getVehiclesViewAttribute()
    {
        return $this->getMeta('vehicles_view');
    }

    public function getAlertsViewAttribute()
    {
        return $this->getMeta('alerts_view');
    }

    public function getAccountActivatedAttribute()
    {
        return $this->password !== null;
    }

    public function getProductsViewAttribute()
    {
        return $this->getMeta('products_view');
    }

    public function getAlertsMatchViewAttribute()
    {
        return $this->getMeta('alerts_match_view');
    }

    public function getMatchsViewAttribute()
    {
        return $this->getMeta('matchs_view');
    }

    public function setPasswordAttribute($password)
    {
        if (! empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'firstname', 'lastname', 'address', 'city', 'email', 'password',
            'country_id', 'role', 'zip', 'password_reset_token', 'lang', 'region', 'phone',
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden
        = [
            'password', 'remember_token',
        ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
        ];

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function region()
    {
        return $this->hasOne('App\Models\Region', 'id', 'region');
    }

    public function models()
    {
        return $this->belongsToMany(
            'App\Models\Modele',
            'users_modeles',
            'user_id',
            'modele_id'
        )
            ->withPivot('car_name');
    }

    public function messagesReceived()
    {
        return $this->hasMany('App\Models\Message', 'recipient');
    }

    public function researches()
    {
        return $this->hasMany('App\Models\Research', 'user_id');
    }
}
