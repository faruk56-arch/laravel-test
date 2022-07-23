<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Modele;
use App\Models\User;
use App\Models\UserModel;
use Faker\Generator as Faker;

$factory->define(UserModel::class, function (Faker $faker) {
    return [
        'modele_id' => Modele::inRandomOrder()->first(),
        'user_id' => User::inRandomOrder()->first(),
        'car_name' => $faker->firstNameFemale,
    ];
});
