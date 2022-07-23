<?php

namespace App\Models;

use App\Actions\AdminModifications\AdminEditable;
use App\Actions\Translations\DeepLTranslation;
use JsonException;
use Kyslik\LaravelFilterable\Filterable;
use OwenIt\Auditing\Auditable;

class Product extends AdminEditable implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    use Filterable;

    protected $with
        = [
            'part', 'modele.brand', 'merchant.country',
            'merchant.user', 'status', 'merchant.region',
        ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name', 'merchant_id', 'price', 'currency', 'stock', 'status', 'description',
            'reference', 'weight', 'delivery_option', 'sent', 'intern', 'public',
        ];
    protected $appends = ['img_with_index', 'translation','original_name','original_description','original_language'];

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'order_items')
            ->withPivot('quantity');
    }

    public function part()
    {
        return $this->belongsTo('App\Models\Part');
    }

    public function modele()
    {
        return $this->belongsToMany('App\Models\Modele', 'product_model');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status');
    }

    public function research()
    {
        return $this->belongsToMany(
            'App\Models\Research',
            'research_product',
            'product_id',
            'research_id'
        )
            ->withPivot('status', 'status_note', 'status_preference', 'id');
    }

    public function sales()
    {
        return $this->hasMany(
            'App\Models\Sale'
        );
    }

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant', 'merchant_id')->withTrashed();
    }

    public function setImgAttribute($value)
    {
        try {
            $imagesBdd = [];
            foreach ($value as $i => $img) {
                array_push($imagesBdd, ['index' => $i, 'url' => $img]);
            }
            $this->attributes['img'] = json_encode($imagesBdd, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            \Log::info($e);
        }
    }

    public function setConditionAttribute($value)
    {
        $this->attributes['condition'] = $value === 'null' ? null : $value;
    }

    public function getImgAttribute()
    {
        try {
            $img = json_decode($this->attributes['img'], true);
            if (! $img) {
                return;
            }
            $response = [];
            foreach ($img as $im) {
                if (isset($im['url'])) {
                    array_push($response, $im['url']);
                }
            }

            return $response;
        } catch (JsonException $e) {
            \Log::info($e);
        }
    }

    public function getImgWithIndexAttribute()
    {
        try {
            return json_decode($this->attributes['img'], true);
        } catch (JsonException $e) {
            \Log::info($e);
        }
    }
    public function getOriginalNameAttribute()
    {
        return $this->attributes['name'];
    }
    public function getOriginalDescriptionAttribute()
    {
        return $this->attributes['description'];
    }
    public function getOriginalLanguageAttribute()
    {
        if($translation = DeepLTranslation::getTranslation($this, 'name')){
            return $translation->language;
        }else if($translation = DeepLTranslation::getTranslation($this, 'description')){
            return $translation->language;
        }
    }
    public function getNameAttribute()
    {
        return translateObject('name', $this);
    }

    public function getDescriptionAttribute()
    {
        return translateObject('description', $this);
    }
}
