<?php

namespace App\Models;

class Sale extends BaseModel
{
    protected $fillable = ['status_preference'];

    protected $table = 'research_product';

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function research()
    {
        return $this->belongsTo('App\Models\Research', 'research_id');
    }
}
