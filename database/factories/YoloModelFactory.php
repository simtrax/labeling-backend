<?php

use Faker\Generator as Faker;

$factory->define(App\YoloModel::class, function (Faker $faker) {
    return [
        'title' => $faker->text(rand(10,15)),
    ];
});
