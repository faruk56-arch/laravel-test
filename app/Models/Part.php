<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\LaravelFilterable\Filterable;
use Laravel\Scout\Searchable;
use Log;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;

class Part extends BaseModel
{
    use Filterable;
    use Searchable;

    public $asYouType = true;

    protected $appends = ['name', 'translation', 'bywords'];

    protected $with = ['category', 'modele'];

    protected $guarded = ['id'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        if (!array_key_exists('name', $this->translation)) {
            return [];
        }
        $unwanted_array = [
            'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A',
            'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B',
            'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
            'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i',
            'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ü' => 'u', 'ú' => 'u', 'û' => 'u',
            'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y',
        ];
        $res = [];
        $res['id'] = $this->id;
        $res['fr'] = $this->translation['name']['fr_FR'];
        $res['frU'] = strtr($this->translation['name']['fr_FR'],
            $unwanted_array
        );
        $res['en'] = $this->translation['name']['en_EN'];
        $res['enU'] = strtr($this->translation['name']['en_EN'],
            $unwanted_array
        );
        $res['de'] = $this->translation['name']['de_DE'];
        $res['deU'] = strtr($this->translation['name']['de_DE'],
            $unwanted_array
        );
        if ($this->bywords && $this->byword != 'null') {
            foreach ($this->bywords as $i => $byword) {
                $res['byWord' . $i] = $byword;
                $res['byWordNGramsAccents' . $i]
                    = strtr(
                    (new TNTIndexer)->buildTrigrams($byword),
                    $unwanted_array
                );
                $res['byWordNGrams' . $i]
                    = (new TNTIndexer)->buildTrigrams($byword);
            }
        }

        return $res;
    }

    public function research()
    {
        return $this->hasMany(
            'App\Models\Research'
        );
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function modele()
    {
        return $this->belongsTo('App\Models\Modele');
    }

    public function alerts()
    {
        return $this->hasMany('App\Models\AlertSubscription');
    }

    public function getByWordsAttribute()
    {
        try {
            $bywords = Translation::where(
                [
                    ['type', $this->table], ['type_id', $this->id],
                ]
            )->firstOrFail()->toArray()['bywords'];

            if ($bywords) {
                return json_decode($bywords, true, 512, JSON_THROW_ON_ERROR);
            }

            return;
        } catch (\Exception $e) {
            return;
        }
    }

    public function setByWordsAttribute($words)
    {
        if (is_null($words)) {
            return;
        }
        Translation::where(
            [
                ['type', $this->table], ['type_id', $this->id],
            ]
        )->update(['bywords' => $words]);
    }

    public function getNameAttribute()
    {
        return translateObject('name', $this);
    }
}
