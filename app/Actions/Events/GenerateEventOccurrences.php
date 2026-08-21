<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Enums\EventStatus;
use App\Enums\SlotStatus;
use App\Models\Event;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

class GenerateEventOccurrences
{
    /**
     * Materialize every weekly occurrence for a recurring template.
     *
     * @param  array<int, array{airline_icao: string, flight_number: string, aircraft: string, origin: string, destination: string, departs_at: string, arrives_at?: string|null, gate?: string|null}>  $pilotSlots
     * @param  array<int, array{callsign: string, starts_at: string, ends_at: string}>  $atcSlots
     */
    public function handle(Event $template, array $pilotSlots = [], array $atcSlots = []): void
    {
        foreach ($this->occurrenceDayOffsets($template) as $dayOffset) {
            $occurrence = $template->occurrences()->create([
                'name' => $template->name,
                'name_en' => $template->name_en,
                'description' => $template->description,
                'description_en' => $template->description_en,
                'slug' => Str::slug($template->name).'-'.Str::random(2),
                'image_url' => $template->image_url,
                'type' => $template->type,
                'tags' => $template->tags,
                'locations' => $template->locations,
                'starts_at' => $template->starts_at->addDays($dayOffset),
                'ends_at' => $template->ends_at?->addDays($dayOffset),
                'pilot_slots_enabled' => $template->pilot_slots_enabled,
                'atc_slots_enabled' => $template->atc_slots_enabled,
                'status' => EventStatus::ACTIVE,
                'is_recurring' => false,
                'created_by' => $template->created_by,
            ]);

            if ($pilotSlots !== []) {
                $occurrence->pilotSlots()->createMany(array_map(fn (array $slot): array => [
                    'airline_icao' => $slot['airline_icao'],
                    'flight_number' => $slot['flight_number'],
                    'aircraft' => $slot['aircraft'],
                    'origin' => $slot['origin'],
                    'destination' => $slot['destination'],
                    'departs_at' => $this->shift($slot['departs_at'], $dayOffset),
                    'arrives_at' => isset($slot['arrives_at']) ? $this->shift($slot['arrives_at'], $dayOffset) : null,
                    'gate' => $slot['gate'] ?? null,
                    'status' => SlotStatus::AVAILABLE,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $pilotSlots));
            }

            if ($atcSlots !== []) {
                $occurrence->atcSlots()->createMany(array_map(fn (array $slot): array => [
                    'callsign' => $slot['callsign'],
                    'starts_at' => $this->shift($slot['starts_at'], $dayOffset),
                    'ends_at' => $this->shift($slot['ends_at'], $dayOffset),
                    'status' => SlotStatus::AVAILABLE,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $atcSlots));
            }
        }
    }

    /**
     * Whole-day offsets (from the template start date) of every matching occurrence date.
     *
     * @return array<int, int>
     */
    private function occurrenceDayOffsets(Event $template): array
    {
        $weekdays = $template->recurrence_weekdays ?? [];
        $interval = max(1, $template->recurrence_interval ?? 1);
        $weekAnchor = $template->starts_at->startOfWeek();
        $startDate = $template->starts_at->startOfDay();
        $endDate = $template->recurrence_ends_at?->endOfDay() ?? $startDate;

        $offsets = [];
        for ($date = $startDate; $date->lessThanOrEqualTo($endDate); $date = $date->addDay()) {
            $weekIndex = (int) $weekAnchor->diffInWeeks($date);

            if ($weekIndex % $interval === 0 && in_array($date->dayOfWeek, $weekdays, true)) {
                $offsets[] = (int) $startDate->diffInDays($date);
            }
        }

        return $offsets;
    }

    private function shift(string $dateTime, int $dayOffset): string
    {
        return CarbonImmutable::parse($dateTime)->addDays($dayOffset)->format('Y-m-d H:i');
    }
}
