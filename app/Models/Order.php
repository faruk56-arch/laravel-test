<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'order_items')->withPivot('quantity');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class,'region');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }
}
