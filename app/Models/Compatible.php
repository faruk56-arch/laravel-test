<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compatible extends Model
{
    protected $table = 'model_compatible';

    protected $guarded = ['id'];

    public function getModeleCompatibleIdAttribute()
    {
        return Modele::find($this->attributes['modele_compatible_id'])
            ->makeHidden('compatible_modeles');
    }
}
