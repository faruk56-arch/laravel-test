<?php

namespace App\Repositories;

use App\Models\Taxonomy;

class TaxonomyRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return Taxonomy::class;
    }
}
