<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'status' => $faker->numberBetween(0, 4),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
    ];
});
