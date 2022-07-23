<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Part;
use App\Models\Research;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Research::class, function (Faker $faker) {
    $status = [
        'in_progress',
        'finished',
        'archived',
    ];

    return [
        'name'      => $faker->word,
        'status'    => $status[rand(0, 2)],
        'user_id'   => $faker->randomElement(User::all()->pluck('id')
            ->toArray()),
        'part_id'   => $faker->randomElement(Part::all()->pluck('id')
            ->toArray()),
        'modele_id' => \App\Models\UserModel::inRandomOrder()->first()->id,
        'reference'=>$faker->numberBetween(0, 999999),
    ];
});
