<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Kyslik\LaravelFilterable\Generic\Filter;

class ResearchFilter extends Filter
{
    /**
     * Defines columns that end-user may filter by.
     *
     * @var array
     */
    protected $filterables = [
        'id', 'name', 'created_at', 'updated_at', 'user_id', 'status',
    ];

    /**
     * @return array
     */
    public function filterMap(): array
    {
        return ['modele'=> 'modele'];
    }

    /**
     * @param  $termId
     * @return Builder
     */
    public function modele($modeleId)
    {
        return $this->builder->where('modele_id', $modeleId);
    }

    /**
     * Define allowed generics, and for which fields.
     * Read more in the documentation https://github.com/Kyslik/laravel-filterable#additional-configuration.
     *
     * @return void
     */
    protected function settings()
    {
        //
    }
}
