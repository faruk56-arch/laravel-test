<?php

namespace App\Repositories;

use App\Models\Merchant;

class MerchantRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return Merchant::class;
    }
}
