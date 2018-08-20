<?php

use Faker\Generator as Faker;
use App\Forum\Category\Models\Category;
use App\Forum\Topic\Models\Topic;

$factory->define(Topic::class, function (Faker $faker) {

    return [
        'category_id' => function() {
            return factory(Category::class)->create()->id;
        },
        'title' => 'Título de teste',
        'content' => 'Conteúdo de teste.',
        'active' => 1
    ];
});
