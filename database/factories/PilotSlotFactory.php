<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SlotStatus;
use App\Models\Event;
use App\Models\PilotSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PilotSlot>
 */
class PilotSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'callsign' => fake()->regexify('[A-Za-z0-9]{7}'),
            'aircraft' => fake()->regexify('[A-Za-z0-9]{4}'),
            'origin' => fake()->regexify('[A-Za-z0-9]{10}'),
            'destination' => fake()->regexify('[A-Za-z0-9]{10}'),
            'departs_at' => fake()->dateTime(),
            'status' => SlotStatus::AVAILABLE,
        ];
    }

    public function unavailable(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => SlotStatus::UNAVAILABLE,
        ]);
    }

    public function cancelled(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => SlotStatus::CANCELLED,
        ]);
    }
}
