<?php

namespace App\Filters;

use Kyslik\LaravelFilterable\Filter;

class CategoryFilter extends Filter
{
    /**
     * Available Filters and their aliases.
     *
     * @return array ex: ['method-name', 'another-method' => 'alias', 'yet-another-method' => ['alias-one', 'alias-two]]
     */
    public function filterMap(): array
    {
        return ['modele'=>['modele_id', 'modele']];
    }

    public function modele($id)
    {
        return $this->builder->whereHas('modeles', function ($q) use ($id) {
            $q->where('id', $id);
        })->withCount(['parts'=> function ($query) use ($id) {
            $query->where('modele_id', $id);
        }]);
    }
}
