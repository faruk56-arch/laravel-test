<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return Order::class;
    }
}
