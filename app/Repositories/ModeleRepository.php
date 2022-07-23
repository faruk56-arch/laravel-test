<?php

namespace App\Repositories;

use App\Models\Modele;

class ModeleRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return Modele::class;
    }
}
