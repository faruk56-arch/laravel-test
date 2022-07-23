<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use App\Models\Part;
use Faker\Generator as Faker;

$factory->define(Part::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Fakecar($faker));

    return [
        'category_id' => Category::inRandomOrder()->first()->id,
        'modele_id' => null,
    ];
});
