<?php

namespace App\Services\MergeModels;

class MergeBrands extends MergingObjectRelationsAbstract
{
    public function relationsWithAttribute(): array
    {
        return ['models' => 'brand_id'];
    }
}
