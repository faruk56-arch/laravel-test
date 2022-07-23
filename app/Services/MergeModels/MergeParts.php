<?php

namespace App\Services\MergeModels;

class MergeParts extends MergingObjectRelationsAbstract
{
    public function relationsWithAttribute(): array
    {
        return [
            'research' => 'part_id',
            'products' => 'part_id',
            'alerts'   => 'part_id',
        ];
    }

    //end relationsWithAttribute()
}//end class
