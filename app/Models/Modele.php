<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\LaravelFilterable\Filterable;
use Laravel\Scout\Searchable;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;

class Modele extends BaseModel
{
    use Filterable;
    use Searchable;

    public $asYouType = true;

    protected $with = ['brand:id,name'];

    protected $appends = ['compatible_modeles'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $parts = explode('-', $this->brand['name']);
        $response = [
            'id'          => $this->id,
            'name'        => $this->name,
//            'nameNgrams'  => utf8_encode((new TNTIndexer)->buildTrigrams($this->name)),
            'brand'       => $this->brand['name'],
//            'brandNgrams' => utf8_encode((new TNTIndexer)->buildTrigrams($this->brand['name'])),
        ];
        foreach ($parts as $i=>$p) {
            $response[$i] = $p;
        }

        return $response;
    }

    protected $guarded = ['id'];

    protected $table = 'modeles';

    public function users()
    {
        return $this->belongsToMany(
            'App\Models\User',
            'users_modeles',
            'modele_id',
            'user_id'
        )
            ->withPivot('car_name');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_model');
    }

    public function productsWithout()
    {
        return $this->belongsToMany('App\Models\Product', 'product_model')->without(['merchant', 'modele']);
    }

    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category',
            'modeles_categories',
            'modele_id',
            'category_id'
        );
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function compatible()
    {
        return $this->hasMany(Compatible::class);
    }

    public function getCompatibleModelesAttribute()
    {
        return $this->compatible->map(function ($item) {
            return $item->modele_compatible_id;
        });
    }

    public function getNameAttribute()
    {
        return is_null($this->attributes['name']) ? '' : $this->attributes['name'];
    }

    public function setCompatibleModelesAttribute($value)
    {
        $this->compatible()->delete();
        foreach ($value as $car) {
            Compatible::create([
                'modele_id'            => $this->id,
                'modele_compatible_id' => $car['id'],
            ]);
        }
    }
}
