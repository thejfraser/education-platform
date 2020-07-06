<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'slug' => $faker->slug,
        'title' => $faker->words(5, true),
        'excerpt' => $faker->paragraph(),
        'body' => $faker->paragraphs(3, true),
        'author_id' => function() {
            return factory(\App\User::class)->create()->id;
        },
        'published_at' => rand(0, 1) === 1 ? now() : null
    ];
});
