<?php

use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$UEaj2ksyJYME/wq0UDrvo.WATlHsh2wjONn3fvM7x/3c/qYtLdky6',
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Channel::class, function (Faker\Generator $faker) {
    $name = $faker->company;

    return [
        'user_id' => factory(App\User::class)->create()->id,
        'name' => $name,
        'slug' => Str::slug($name),
    ];
});

$factory->define(App\Bot::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(App\User::class)->create()->id,
        'external_id' => $faker->randomNumber,
        'name' => $faker->name,
        'username' => $faker->username.'Bot',
        'token' => Str::random(16),
    ];
});

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(App\User::class)->create()->id,
        'message' => $faker->paragraphs(3, true),
    ];
});
