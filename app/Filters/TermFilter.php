<?php

namespace App\Filters;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\LaravelFilterable\Generic\Filter;

class TermFilter extends Filter
{
    /**
     * @return array
     */
    public function filterMap(): array
    {
        return ['parent' => 'parent', 'userId' => ['user_id', 'user_terms']];
    }

    /**
     * @param  null $parent
     * @return Builder
     */
    public function parent($parent = null)
    {
        if ($parent === null || strtoupper($parent) === 'NULL') {
            return $this->builder->whereNull('parent');
        }

        return $this->builder->where('parent', $parent);
    }

    /**
     * @param  $userId
     * @return Builder
     */
    public function userId($userId = null)
    {
        return $this->builder->with(
            [
            'users' => function ($query) {
                $query->select('car_name')->whereNotNull('car_name');
            }, ]
        )->whereHas(
            'users',
            function ($query) use ($userId) {
                $query->select('id');
                if (Auth::user()->role !== 'user') {
                    return $query->where('id', $userId);
                }

                return $query->where('id', Auth::id());
            }
        );
    }

    /**
     * Define allowed generics, and for which fields.
     * Read more in the documentation
     * https://github.com/Kyslik/laravel-filterable#additional-configuration.
     *
     * @return void
     */
    protected function settings()
    {
        //
    }
}
