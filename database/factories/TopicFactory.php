<?php

use Faker\Generator as Faker;
use App\Forum\Category\Models\Category;
use App\Forum\User\Models\User;
use App\Forum\Topic\Models\Topic;

$factory->define(Topic::class, function (Faker $faker) {

    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'category_id' => function() {
            return factory(Category::class)->create()->id;
        },
        'title' => 'Título de teste',
        'content' => 'Conteúdo de teste.',
        'active' => true
    ];
});
