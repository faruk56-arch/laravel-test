<?php

/** @var Factory $factory */

use App\Models\Translation;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(
    Translation::class,
    function (Faker $faker) {
        $word = $faker->domainWord;

        return [
            'language' => $faker->languageCode,
            'type'     => $faker->word,
            'type_id'  => $faker->numberBetween(0, 20),
            'key'      => $word,
            'fr_FR'    => $word,
            'en_EN'    => $word,
            'de_DE'    => $word,
        ];
    }
);
