<?php

namespace App\Services\MergeModels;

use App\Models\Compatible;

class MergeModeles extends MergingObjectRelationsAbstract
{
    public function relationsWithAttribute(): array
    {
        return [
            'users'      => 'modele_id',
            'categories' => 'modele_id',
            'compatible' => 'modele_id',
        ];
    }

    //end relationsWithAttribute()

    public function beforeDelete()
    {
        Compatible::where('modele_compatible_id', $this->oldModel->id)->update(['modele_compatible_id' => $this->newModel->id]);
    }

    //end beforeDelete()
}//end class
