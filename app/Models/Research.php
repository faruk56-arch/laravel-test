<?php

namespace App\Models;

use App\Actions\AdminModifications\AdminEditable;
use App\Actions\Translations\DeepLTranslation;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JsonException;
use Kyslik\LaravelFilterable\Filterable;
use OwenIt\Auditing\Auditable;
use Plank\Metable\Metable;

class Research extends AdminEditable implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    use Metable;
    use Filterable;

    protected $with = ['user', 'part'];
    protected $withCount = ['products', 'alerts'];

    protected $table = 'researches';

    protected $appends = ['types', 'translation','original_details','original_language'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name',
            'status',
            'modele_id',
            'user_id',
            'part_id',
            'reference',
            'details',
            'unknown_part',
            'img',
            'still',
            'type',
            'compatible_suggestion',
            'public',
        ];

    protected $casts
        = [
            'unknown_part' => 'json',
        ];

    public function part()
    {
        return $this->belongsTo('App\Models\Part', 'part_id');
    }

    public function modele()
    {
        return $this->belongsTo(
            'App\Models\UserModel',
            'modele_id'
        );
    }

    public function products()
    {
        return $this->belongsToMany(
            'App\Models\Product',
            'research_product',
            'research_id',
            'product_id'
        )
            ->withPivot('status', 'status_note', 'status_preference', 'id');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function alerts()
    {
        return $this->belongsToMany(
            'App\Models\AlertSubscription',
            'alert_subscription_researches',
            'research_id',
            'alert_subscription_id'
        );
    }

    public function types()
    {
        return $this->belongsToMany(
            'App\Models\Status',
            'research_type',
            'research_id',
            'type_id'
        );
    }

    public function productMissed(): BelongsToMany
    {
        return $this->belongsToMany(SoldProduct::class)->with('research');
    }

    public function productSold(): HasOne
    {
        return $this->hasOne(SoldProduct::class);
    }

    public function getImgAttribute()
    {
        try {
            if (!array_key_exists('img', $this->attributes)) {
                return [];
            }
            $img = json_decode($this->attributes['img'], true);
            if (! $img) {
                return [];
            }
            $response = [];
            foreach ($img as $im) {
                array_push($response, $im);
            }

            return $response;
        } catch (JsonException $e) {
            \Log::info($e);
        }
    }

    public function getDetailsAttribute()
    {
        return translateObject('details', $this);
    }
    public function getOriginalDetailsAttribute()
    {
        return $this->attributes['details'];
    }
    public function getOriginalLanguageAttribute()
    {
        if($translation = DeepLTranslation::getTranslation($this, 'details')){
            return $translation->language;
        }
    }

    public function getTypesAttribute()
    {
        return DB::table('research_type')->where('research_id', $this->id)
            ->pluck('type_id');
    }

    public function setImgAttribute($value)
    {
        try {
            $this->attributes['img'] = json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            \Log::info($e);
        }
    }
}
