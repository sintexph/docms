<?php

use Faker\Generator as Faker;
use App\System;

$factory->define(App\Section::class, function (Faker $faker) {
    return [
        'code'=>$faker->unique()->countryCode,
        'name'=>strtoupper($faker->word),
        'system_code'=>System::inRandomOrder()->First()->code,
    ];
});
