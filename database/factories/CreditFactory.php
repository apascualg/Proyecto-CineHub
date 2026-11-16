<?php

namespace Database\Factories;

use App\Models\Credit;
use App\Models\Person;
use App\Models\Title;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Credit>
 */
class CreditFactory extends Factory
{
    protected $model = Credit::class;

    public function definition(): array
    {
        $roleType = fake()->randomElement(['actor', 'director', 'creator']);

        return [
            'title_id' => Title::factory(),
            'person_id' => Person::factory(),
            'role_type' => $roleType,
            'character_name' => $roleType === 'actor' ? fake()->name() : null,
            'billing_order' => fake()->numberBetween(1, 20),
        ];
    }

    public function actor(): static
    {
        return $this->state(fn (): array => [
            'role_type' => 'actor',
            'character_name' => fake()->name(),
        ]);
    }

    public function director(): static
    {
        return $this->state(fn (): array => [
            'role_type' => 'director',
            'character_name' => null,
            'billing_order' => 1,
        ]);
    }
}
