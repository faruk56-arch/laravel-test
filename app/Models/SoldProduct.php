<?php

namespace App\Models;

use JsonException;

class SoldProduct extends BaseModel
{
    protected $guarded = ['id'];

    protected $appends = ['img_with_index', 'translation'];
    protected $with = ['merchant.user', 'merchant.country', 'merchant.region'];

    public function getImgAttribute()
    {
        try {
            $img = json_decode($this->attributes['img'], true);
            if (! $img) {
                return;
            }

            $response = [];
            foreach ($img as $im) {
                array_push($response, $im['url']);
            }

            return $response;
        } catch (JsonException $e) {
            \Log::info($e);
        }
    }

    //end getImgAttribute()

    public function getImgWithIndexAttribute()
    {
        try {
            return json_decode($this->attributes['img'], true);
        } catch (JsonException $e) {
            \Log::info($e);
        }
    }


    public function getTranslationAttribute()
    {
        return Translation::where(
            [
                ['type', 'products'], ['type_id', $this->old_id],
            ]
        )
            ->select(['fr_FR', 'en_EN', 'de_DE', 'key'])
            ->get()
            ->keyBy('key')->toArray();
    }

    //end getImgWithIndexAttribute()

    /**
     * @return mixed
     */
    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant', 'merchant_id')->withTrashed();
    }

    public function research(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Research', 'research_id');
    }
}//end class
