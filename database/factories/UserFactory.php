<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'vid' => fake()->unique()->numberBetween(100000, 999999),
            'division' => fake()->countryCode(),
            'atc_rating' => fake()->numberBetween(1, 10),
            'pilot_rating' => fake()->numberBetween(1, 10),
        ];
    }
}
