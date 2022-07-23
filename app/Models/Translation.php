<?php

namespace App\Models;

class Translation extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language', 'type', 'type_id', 'key', 'fr_FR', 'en_EN', 'de_DE', 'bywords',
    ];
}
