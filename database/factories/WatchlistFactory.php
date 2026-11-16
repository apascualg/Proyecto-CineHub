<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Watchlist>
 */
class WatchlistFactory extends Factory
{
    protected $model = Watchlist::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement(['Favoritos', 'Pendientes', 'Vistos']),
            'type' => fake()->randomElement(['favorites', 'pending', 'watched', 'custom']),
            'description' => fake()->optional()->sentence(),
            'is_public' => false,
        ];
    }

    public function public(): static
    {
        return $this->state(fn (): array => ['is_public' => true]);
    }
}
