<?php

namespace App\Filters;

use Auth;
use Kyslik\LaravelFilterable\Filter;

class ModeleFilter extends Filter
{
    /**
     * Available Filters and their aliases.
     *
     * @return array ex: ['method-name', 'another-method' => 'alias', 'yet-another-method' => ['alias-one', 'alias-two]]
     */
    public function filterMap(): array
    {
        return ['userId' => ['user_id', 'user_modeles']];
    }

    public function userId($userId = null)
    {
        return $this->builder->whereHas('users', function ($q) use ($userId) {
            if (Auth::user()->role !== 'user') {
                $q->where('user_id', $userId);
            } else {
                $q->where('user_id', Auth::id());
            }
        })->with(['users'=>function ($q) use ($userId) {
            if (Auth::user()->role !== 'user') {
                $q->where('user_id', $userId);
            } else {
                $q->where('user_id', Auth::id());
            }
        }]);
//        return $this->builder->with(
//            [
//                'users' =>
//                    function ($query) {
//                        $query->select('car_name')->whereNotNull('car_name');
//                    }]
//        )->whereHas(
//            'users',
//            function ($query) use ($userId) {
//                $query->select('id');
//                if (Auth::user()->role !== 'user') {
//                    return $query->where('id', $userId);
//                }
//                return $query->where('id', Auth::id());
//            }
//        );
    }
}
