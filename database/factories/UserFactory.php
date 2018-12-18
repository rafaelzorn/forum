<?php

use Faker\Generator as Faker;
use App\Forum\User\Models\User;

$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'active'         => true,
    ];
});

$factory->defineAs(User::class, 'admin', function ($faker) use ($factory) {
    $user = $factory->raw(User::class);

    return array_merge($user, ['is_admin' => true]);
});
