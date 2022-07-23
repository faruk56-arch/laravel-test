<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id', 'country_id', 'name', 'code',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
