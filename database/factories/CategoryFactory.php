<?php

use Faker\Generator as Faker;
use App\Forum\Category\Models\Category;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name'   => $faker->name,
        'active' => true
    ];
});
