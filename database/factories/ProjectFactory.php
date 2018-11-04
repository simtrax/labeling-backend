<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {

    return [
        'title' => $faker->text(rand(5,15)),
        'description' => $faker->text(rand(50,100))
    ];
});
