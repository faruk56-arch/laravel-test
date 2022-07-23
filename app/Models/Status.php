<?php

namespace App\Models;

class Status extends BaseModel
{
    protected $appends = ['translation'];

    protected $fillable = ['class'];

    protected $table = 'status';
}
