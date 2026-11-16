<?php

namespace Database\Factories;

use App\Models\Title;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Title>
 */
class TitleFactory extends Factory
{
    protected $model = Title::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(random_int(2, 4));

        return [
            'type' => fake()->randomElement(['movie', 'series']),
            'title' => $title,
            'original_title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1000, 9999),
            'synopsis' => fake()->paragraph(),
            'release_date' => fake()->dateTimeBetween('-20 years', 'now')->format('Y-m-d'),
            'duration_minutes' => fake()->optional()->numberBetween(80, 180),
            'seasons_count' => fake()->optional()->numberBetween(1, 8),
            'episodes_count' => fake()->optional()->numberBetween(6, 100),
            'poster_path' => null,
            'backdrop_path' => null,
            'trailer_url' => null,
            'status' => fake()->randomElement(['published', 'published', 'draft']),
        ];
    }

    public function movie(): static
    {
        return $this->state(fn (): array => [
            'type' => 'movie',
            'duration_minutes' => fake()->numberBetween(80, 180),
            'seasons_count' => null,
            'episodes_count' => null,
        ]);
    }

    public function series(): static
    {
        return $this->state(fn (): array => [
            'type' => 'series',
            'duration_minutes' => null,
            'seasons_count' => fake()->numberBetween(1, 8),
            'episodes_count' => fake()->numberBetween(6, 100),
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (): array => ['status' => 'published']);
    }
}
