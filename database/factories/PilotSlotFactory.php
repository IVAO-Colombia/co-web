<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PilotSlotCategory;
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
        $departsAt = fake()->dateTime();

        return [
            'event_id' => Event::factory(),
            'airline_icao' => fake()->regexify('[A-Z]{3}'),
            'flight_number' => fake()->regexify('[0-9]{2,4}'),
            'aircraft' => fake()->regexify('[A-Za-z0-9]{4}'),
            'origin' => fake()->regexify('[A-Za-z0-9]{10}'),
            'destination' => fake()->regexify('[A-Za-z0-9]{10}'),
            'category' => PilotSlotCategory::DEPARTURE,
            'departs_at' => $departsAt,
            'arrives_at' => (clone $departsAt)->modify('+2 hours'),
            'status' => SlotStatus::AVAILABLE,
        ];
    }

    public function departure(): self
    {
        return $this->state(fn (array $attributes) => [
            'category' => PilotSlotCategory::DEPARTURE,
        ]);
    }

    public function arrival(): self
    {
        return $this->state(fn (array $attributes) => [
            'category' => PilotSlotCategory::ARRIVAL,
        ]);
    }

    public function reserved(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => SlotStatus::RESERVED,
        ]);
    }

    public function confirmed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => SlotStatus::CONFIRMED,
        ]);
    }
}
