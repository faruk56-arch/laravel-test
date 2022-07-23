<?php

namespace App\Repositories;

use App\Models\Part;

class PartRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return Part::class;
    }
}
