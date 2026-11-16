<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Genre>
 */
class GenreFactory extends Factory
{
    protected $model = Genre::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Acción',
            'Animación',
            'Aventura',
            'Ciencia ficción',
            'Comedia',
            'Crimen',
            'Drama',
            'Fantasía',
            'Terror',
            'Thriller',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
