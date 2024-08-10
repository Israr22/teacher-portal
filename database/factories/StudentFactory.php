<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => strtolower($this->faker->name),
            'subject' => strtolower($this->faker->word),
            'marks' => $this->faker->numberBetween(0, 100),
            'user_id' => \App\Models\User::factory()
        ];
    }
}
