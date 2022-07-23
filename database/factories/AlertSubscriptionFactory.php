<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

use App\Models\AlertSubscription;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Modele;
use Faker\Generator as Faker;

$factory->define(
    AlertSubscription::class,
    function (Faker $faker) {
        $modele = Modele::inRandomOrder()->first();
        $category = Category::whereHas(
            'modeles',
            function ($query) use ($modele) {
                $query->where('id', $modele->id);
            }
        )->inRandomOrder()->first();

        return [
            'modele_id'   => $modele->id,
            'brand_id'    => $modele->brand_id,
            'category_id' => $category->id,
            'part_id'     => $category->parts()->inRandomOrder()->first()->id,
            'merchant_id' => Merchant::first()->id,
            'active' => 1,
        ];
    }
);
