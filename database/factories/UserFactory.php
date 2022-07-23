<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Country;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'firstname'         => $faker->firstName,
        'lastname'         => $faker->lastName,
        'address'         => $faker->streetAddress,
        'city'         => $faker->city,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'zip'               => '123456',
        'password'          => 'password', // password
        'remember_token'    => Str::random(10),
        'country_id'        => $faker->randomElement(Country::all()->pluck('id')
            ->toArray()),
        'merchant_id'       => null,
    ];
});
