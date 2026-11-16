<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title_id' => Title::factory(),
            'headline' => fake()->optional()->sentence(6),
            'body' => fake()->paragraphs(3, true),
            'status' => 'pending',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (): array => ['status' => 'approved']);
    }

    public function hidden(): static
    {
        return $this->state(fn (): array => ['status' => 'hidden']);
    }
}
