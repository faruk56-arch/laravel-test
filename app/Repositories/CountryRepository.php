<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return Country::class;
    }
}
