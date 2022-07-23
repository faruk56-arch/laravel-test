<?php

namespace App\Models;

class Merchant extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'merchant_name', 'merchant_city', 'country_id', 'merchant_type',
            'merchant_siret', 'merchant_zip', 'merchant_address', 'region',
            'phone',
            'stripe_connect_id',
            'completed_stripe_onboarding',
        ];

    protected $with = ['country', 'region'];

    protected $casts = [
        'completed_stripe_onboarding' => 'bool',
    ];

    public function user()
    {
        return $this->hasMany('App\Models\User')->withTrashed();
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'merchant_id');
    }

    public function alerts()
    {
        return $this->hasMany('App\Models\AlertSubscription');
    }

    public function region()
    {
        return $this->hasOne('App\Models\Region', 'id', 'region');
    }
}
