<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\LaravelFilterable\Filterable;

class Category extends BaseModel
{
    use SoftDeletes;
    use Filterable;

    protected $appends = ['translation'];

    public function modeles()
    {
        return $this->belongsToMany('App\Models\Modele', 'modeles_categories');
    }

    public function parts()
    {
        return $this->hasMany('App\Models\Part', 'category_id');
    }
}
