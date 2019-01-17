<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {

    return [
        'title' => $faker->text(rand(10,15)),
        'description' => $faker->text(rand(50,100)),
        'minZoom' => rand(10, 15),
        'maxZoom' => rand(21, 24),
        'geotif' => $faker->uuid(),
    ];
});
