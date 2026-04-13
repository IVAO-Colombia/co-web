<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\UserAward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserAward>
 */
class UserAwardFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'bronze' => $bronze = fake()->numberBetween(0, 20),
            'silver' => $silver = $bronze + fake()->numberBetween(0, 20),
            'gold' => $gold = $silver + fake()->numberBetween(0, 20),
            'platinum' => $platinum = $gold + fake()->numberBetween(0, 20),
            'diamond' => $diamond = $platinum + fake()->numberBetween(0, 20),
        ];
    }
}
