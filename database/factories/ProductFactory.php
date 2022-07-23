<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Merchant;
use App\Models\Product;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
$factory->define(Product::class, function (Faker $faker) {
    $status = [
        1, 2, 3,
    ];
    $condition = [
        12, 13, 14, 15,
    ];

    return [
        'name' => $faker->word,
        'price' => $faker->numberBetween(0, 500) / 10,
        'minPrice' => $faker->numberBetween(0, 500) / 10,
        'shipping_cost' => $faker->numberBetween(0, 200) / 10,
        'shipping_cost_abroad' => $faker->numberBetween(0, 200) / 10,
        'manufacturer' => $faker->word,
        'negotiable' => $faker->boolean,
        'currency' => 'EUR',
        'stock' => $faker->numberBetween(0, 20),
        'reference' => $faker->numberBetween(0, 50) * 10245,
        'description' => $faker->text,
        'status' => $status[rand(0, 2)],
        'condition' => $condition[rand(0, 3)],
        'img' => [$faker->imageUrl(), $faker->imageUrl(), $faker->imageUrl()],
        'merchant_id' => $faker->randomElement(Merchant::all()->pluck('id')->toArray()),
    ];
});
