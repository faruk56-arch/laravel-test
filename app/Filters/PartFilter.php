<?php

namespace App\Filters;

use Kyslik\LaravelFilterable\Filter;

class PartFilter extends Filter
{
    /**
     * Defines columns that end-user may filter by.
     *
     * @var array
     */

    /**
     * Available Filters and their aliases.
     *
     * @return array ex: ['method-name', 'another-method' => 'alias', 'yet-another-method' => ['alias-one', 'alias-two]]
     */
    public function filterMap(): array
    {
        return ['modele'=>['modele_id', 'modele'], 'category'=>['category_id', 'category']];
    }

    public function modele($id)
    {
        return $this->builder->where('modele_id', $id);
    }

    public function category($id)
    {
        return $this->builder->where('category_id', $id);
    }
}
