<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\InterestingProducts;
use Illuminate\Support\Collection;
use Notification;

class InterestingProductsAction
{

    public function query(): Collection
    {
        return User::whereHas('models.products', function ($q) {
            $q->where('products.sent', 1)
                ->where('products.status', 1)
                ->where('products.updated_at', '>', now()->subWeek());
        })
            ->with(['models.productsWithout' => function ($q) {
                $q
                    ->take(7)
                    ->where('products.sent', 1)
                    ->where('products.status', 1)
                    ->where('products.updated_at', '>', now()->subWeek());
            }])
            ->get();
    }

    public function execute()
    {
        $users = $this->query();

        foreach ($users as $user) {
            Notification::send($user,
                new InterestingProducts($user->models));
        }
    }
}
