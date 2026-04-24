<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SlotStatus;
use App\Models\AtcSlot;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AtcSlot>
 */
class AtcSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'callsign' => fake()->regexify('[A-Z]{4}_[A-Z]{2}'),
            'starts_at' => fake()->dateTimeThisYear(),
            'ends_at' => fake()->dateTimeThisYear(),
            'ivao_booking' => null,
            'status' => SlotStatus::AVAILABLE,
        ];
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
