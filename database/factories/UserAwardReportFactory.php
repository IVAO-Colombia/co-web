<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserAwardReportStatus;
use App\Models\Event;
use App\Models\User;
use App\Models\UserAward;
use App\Models\UserAwardReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserAwardReport>
 */
class UserAwardReportFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'award_id' => UserAward::factory(),
            'event_id' => Event::factory(),
            'callsign' => fake()->regexify('[A-Za-z0-9]{25}'),
            'status' => UserAwardReportStatus::PENDING,
            'points' => fake()->numberBetween(-10000, 10000),
            'observations' => fake()->text(),
        ];
    }

    public function approved(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserAwardReportStatus::APPROVED,
        ]);
    }

    public function rejected(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserAwardReportStatus::REJECTED,
        ]);
    }

    public function observation(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserAwardReportStatus::OBSERVATION,
        ]);
    }
}
