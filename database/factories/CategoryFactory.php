<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'code'=>$faker->unique()->countryCode,
        'name'=>strtoupper($faker->word),
    ];
});
