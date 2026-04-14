<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'slug' => fake()->slug(),
            'image_url' => null,
            'type' => EventType::ONLINE_DAY,
            'tags' => [],
            'pilot_slots_enabled' => fake()->boolean(),
            'atc_slots_enabled' => fake()->boolean(),
            'locations' => fake()->regexify('[A-Z]{4} - [A-Z]{4}'),
            'starts_at' => fake()->dateTime(),
            'status' => EventStatus::ACTIVE,
            'created_by' => User::factory(),
        ];
    }

    public function draft(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => EventStatus::DRAFT,
        ]);
    }

    public function cancelled(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => EventStatus::CANCELLED,
        ]);
    }

    public function finalized(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => EventStatus::FINALIZED,
        ]);
    }
}
