<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\UserOAuthToken;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserOAuthToken>
 */
class UserOAuthTokenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'access_token' => fake()->sha256(),
            'refresh_token' => fake()->sha256(),
            'expires_at' => now()->addHour(),
            'scopes' => ['profile', 'email', 'bookings:read', 'bookings:write'],
        ];
    }

    public function expired(): static
    {
        return $this->state([
            'expires_at' => now()->subHour(),
        ]);
    }

    public function withoutRefreshToken(): static
    {
        return $this->state([
            'refresh_token' => null,
        ]);
    }
}
