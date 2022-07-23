<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\LaravelFilterable\Filterable;
use Laravel\Scout\Searchable;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;

class Brand extends BaseModel
{
    use Filterable;

    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany('App\Models\Modele');
    }

    use Searchable;

    public $asYouType = true;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nameNgrams' => utf8_encode((new TNTIndexer)->buildTrigrams($this->name)),
        ];
    }
}
