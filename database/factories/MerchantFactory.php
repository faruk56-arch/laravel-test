<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Country;
use App\Models\Merchant;
use Faker\Generator as Faker;

$factory->define(Merchant::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\fr_FR\Company($faker));
    $status = [
        'Collectionneur',
        'Pro',
    ];

    return [
        'merchant_name' => $faker->word,
        'country_id' => $faker->randomElement(Country::all()->pluck('id')->toArray()),
        'merchant_type' => $faker->randomElement($status),
        'merchant_city' => $faker->city,
        'merchant_address' => $faker->streetAddress,
        'merchant_zip' => $faker->postcode,
        'merchant_siret' => $faker->siret,
    ];
});
