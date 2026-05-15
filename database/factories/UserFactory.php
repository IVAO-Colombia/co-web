<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ATCRating;
use App\Enums\PilotRating;
use App\Enums\Role;
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
            'division_id' => fake()->countryCode(),
            'country_id' => fake()->countryCode(),
            'language_id' => fake()->languageCode(),
            'network_rating' => fake()->numberBetween(1, 10),
            'atc_rating' => fake()->randomElement(ATCRating::cases()),
            'pilot_rating' => fake()->randomElement(PilotRating::cases()),
        ];
    }

    public function director(): self
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(Role::DIR);
        });
    }

    public function trainer(): self
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(Role::T0);
        });
    }
}
