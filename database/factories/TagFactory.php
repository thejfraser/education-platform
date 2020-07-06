<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    $name = $faker->words(2, true);

    return [
        'name' => $name,
        'slug' => \Illuminate\Support\Str::slug($name)
    ];
});
