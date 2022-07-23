<?php

namespace App\Actions\AdminModifications;

use App\Models\BaseModel;
use Illuminate\Support\Collection;
use Plank\Metable\Metable;

abstract class AdminEditable extends BaseModel
{
    use Metable;

    public function modifications(): Collection
    {
        return collect($this->getMeta('modifications'));
    }
}
