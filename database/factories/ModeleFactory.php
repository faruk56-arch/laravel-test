<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

use App\Models\Brand;
use App\Models\Modele;
use Faker\Generator as Faker;

$factory->define(
    Modele::class,
    function (Faker $faker) {
        $faker->addProvider(new \Faker\Provider\Fakecar($faker));

        return [
        'name' => $faker->vehicleModel,
        'year_start' => $faker->year,
        'year_end' => intval($faker->year) + 30,
        'brand_id' => Brand::inRandomOrder()->first()->id,
        ];
    }
);
