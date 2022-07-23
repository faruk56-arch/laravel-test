<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BaseModel.
 *
 * @category Models
 * @author   Thomas Tosch <thomas.tosch@wigomedia.com>
 */
class BaseModel extends Model
{
    use SoftDeletes;

    /**
     * Defines creating model validation rules.
     *
     * @var array
     */
    public static $createRules = [];

    /**
     * Defines updating model validation rules.
     *
     * @var array
     */
    public static $updateRules = [];

    public function saveWithoutEvents(array $options=[])
    {
        return static::withoutEvents(function() use ($options) {
            return $this->save($options);
        });
    }

    /**
     * Returns field from translation table when a model owns a 'translation'
     * field.
     *
     * @return mixed null if there are no translation available
     */
    public function getTranslationAttribute()
    {
        return Translation::where(
            [
                ['type', $this->table], ['type_id', $this->id],
            ]
        )
            ->select(['fr_FR', 'en_EN', 'de_DE', 'key'])
            ->get()
            ->keyBy('key')->toArray();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->formatLocalized('%d %b %Y');
    }
}
