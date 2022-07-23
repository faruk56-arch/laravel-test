<?php

namespace App\Repositories;

use App\Models\Translation;

class TranslationRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return Translation::class;
    }
}
