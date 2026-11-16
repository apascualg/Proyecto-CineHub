<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'biography' => fake()->optional()->paragraphs(2, true),
            'birth_date' => fake()->optional()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'photo_path' => null,
        ];
    }
}
