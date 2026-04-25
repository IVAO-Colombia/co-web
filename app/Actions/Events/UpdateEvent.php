<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Enums\EventStatus;
use App\Enums\SlotStatus;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UpdateEvent
{
    public function handle(UpdateEventRequest $request, Event $event): Event
    {
        DB::transaction(function () use ($request, $event): void {
            $validated = $request->validated();

            $imageUrl = $this->handleImage($request, $event);

            $event->update([
                'name' => $validated['name'],
                'name_en' => $validated['name_en'] ?? null,
                'description' => $validated['description'],
                'description_en' => $validated['description_en'] ?? null,
                'image_url' => $imageUrl,
                'type' => $validated['type'],
                'tags' => $validated['tags'] ?? [],
                'locations' => $validated['locations'],
                'starts_at' => $validated['starts_at'],
                'ends_at' => $validated['ends_at'] ?? null,
                'status' => EventStatus::from($validated['status']),
                'pilot_slots_enabled' => $validated['pilot_slots_enabled'] ?? false,
                'atc_slots_enabled' => $validated['atc_slots_enabled'] ?? false,
            ]);

            if ($event->pilotSlots()->reserved()->doesntExist()) {
                $event->pilotSlots()->forceDelete();

                if (! empty($validated['pilot_slots'])) {
                    $pilotSlots = array_map(fn (array $slot): array => [
                        'event_id' => $event->id,
                        'airline_icao' => $slot['airline_icao'],
                        'flight_number' => $slot['flight_number'],
                        'aircraft' => $slot['aircraft'],
                        'origin' => $slot['origin'],
                        'destination' => $slot['destination'],
                        'departs_at' => $slot['departs_at'],
                        'gate' => $slot['gate'] ?? null,
                        'status' => SlotStatus::AVAILABLE,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $validated['pilot_slots']);
                    $event->pilotSlots()->createMany($pilotSlots);
                }
            }

            if ($event->atcSlots()->reserved()->doesntExist()) {
                $event->atcSlots()->forceDelete();

                if (! empty($validated['atc_slots'])) {
                    $atcSlots = array_map(fn (array $slot): array => [
                        'event_id' => $event->id,
                        'callsign' => $slot['callsign'],
                        'starts_at' => $slot['starts_at'],
                        'ends_at' => $slot['ends_at'],
                        'status' => SlotStatus::AVAILABLE,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $validated['atc_slots']);
                    $event->atcSlots()->createMany($atcSlots);
                }
            }
        });

        return $event->refresh();
    }

    private function handleImage(UpdateEventRequest $request, Event $event): ?string
    {
        if (! $request->hasFile('image')) {
            return $event->image_url;
        }

        if ($event->image_url !== null) {
            $oldPath = str_replace(Storage::disk('public')->url(''), '', $event->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        if (! ($path = $request->file('image')->store('events', 'public'))) {
            throw ValidationException::withMessages(['image' => 'Failed to upload image.']);
        }

        return Storage::disk('public')->url($path);
    }
}
