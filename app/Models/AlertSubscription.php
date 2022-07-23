<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AlertSubscription extends BaseModel
{
    use SoftDeletes;

    protected $fillable
        = [
            'brand_id', 'category_id', 'part_id', 'modele_id', 'merchant_id',
            'active',
        ];

    protected $casts = ['part_id' => 'integer'];

    protected $with
        = [
            'brand:id,name',
            'modele:id,name,year_start,year_end,img',
            'category:id',
            'part.category',
            'researches.part.category',
            'researches.user.country',
            'researches.user.region',
            'merchant.user',
        ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function researches()
    {
        return $this->belongsToMany(
            'App\Models\Research',
            'alert_subscription_researches'
        )->withPivot('dispatched', 'dismiss', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function modele()
    {
        return $this->belongsTo('App\Models\Modele');
    }

    public function part()
    {
        return $this->belongsTo('App\Models\Part');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }
}
