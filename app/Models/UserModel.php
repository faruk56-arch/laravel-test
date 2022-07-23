<?php

namespace App\Models;

use Kyslik\LaravelFilterable\Filterable;

class UserModel extends BaseModel
{
    use Filterable;

    protected $withCount = ['researches'];

    protected $with
        = [
            'modele.brand',
        ];

    protected $table = 'users_modeles';

    protected $fillable
        = [
            'car_name', 'user_id', 'modele_id', 'year', 'version',
        ];

    protected function getVersionAttribute()
    {
        $is_null = is_null($this->attributes['version'])
            || $this->attributes['version'] === 'null';

        return $is_null ? 'Version non-renseignée' : $this->attributes['version'];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function modele()
    {
        return $this->belongsTo('App\Models\Modele', 'modele_id');
    }

    public function researches()
    {
        return $this->hasMany('App\Models\Research', 'modele_id');
    }
}
