<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AtcTraining;
use App\Enums\PilotTraining;
use App\Enums\TrainingRequestStatus;
use App\Enums\TrainingRequestType;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingRequest>
 */
class TrainingRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => TrainingRequestType::ATC,
            'category' => AtcTraining::AdcTheory1->value,
            'status' => TrainingRequestStatus::Pending,
            'occurs_at' => null,
            'internal_observations' => null,
            'public_observations' => null,
            'request_observations' => fake()->sentence(),
            'trainer_id' => null,
            'trainee_id' => User::factory(),
        ];
    }

    public function pending(): self
    {
        return $this->state(['status' => TrainingRequestStatus::Pending]);
    }

    public function scheduled(): self
    {
        return $this->state([
            'status' => TrainingRequestStatus::Scheduled,
            'occurs_at' => now()->addDays(7),
        ]);
    }

    public function cancelled(): self
    {
        return $this->state(['status' => TrainingRequestStatus::Cancelled]);
    }

    public function pilot(): self
    {
        return $this->state([
            'type' => TrainingRequestType::Pilot,
            'category' => PilotTraining::PpTheory1->value,
        ]);
    }
}
