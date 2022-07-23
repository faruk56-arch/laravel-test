<?php

namespace App\Filters;

use Kyslik\LaravelFilterable\Filter;

class ProductFilter extends Filter
{
    /**
     * Available Filters and their aliases.
     *
     * @return array ex: ['method-name', 'another-method' => 'alias', 'yet-another-method' => ['alias-one', 'alias-two]]
     */
    public function filterMap(): array
    {
        return [];
    }
}
