<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Enums\EventStatus;
use App\Enums\SlotStatus;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\TrainingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateEvent
{
    public function __construct(private readonly GenerateEventOccurrences $generateEventOccurrences) {}

    public function handle(StoreEventRequest $request): Event
    {
        $imageUrl = $this->storeFile($request);
        $event = new Event;

        DB::transaction(function () use ($request, $imageUrl, &$event): void {
            $validated = $request->validated();
            $isRecurring = $validated['is_recurring'] ?? false;

            $event = Event::create([
                'name' => $validated['name'],
                'name_en' => $validated['name_en'] ?? null,
                'description' => $validated['description'],
                'description_en' => $validated['description_en'] ?? null,
                'slug' => Str::slug($validated['name']).'-'.Str::random(2),
                'image_url' => $imageUrl,
                'type' => $validated['type'],
                'tags' => $validated['tags'] ?? [],
                'locations' => $validated['locations'],
                'starts_at' => $validated['starts_at'],
                'ends_at' => $validated['ends_at'] ?? null,
                'pilot_slots_enabled' => $validated['pilot_slots_enabled'] ?? false,
                'atc_slots_enabled' => $validated['atc_slots_enabled'] ?? false,
                'status' => EventStatus::from($validated['status']),
                'is_recurring' => $isRecurring,
                'recurrence_interval' => $isRecurring ? $validated['recurrence_interval'] : null,
                'recurrence_weekdays' => $isRecurring ? $validated['recurrence_weekdays'] : null,
                'recurrence_ends_at' => $isRecurring ? $validated['recurrence_ends_at'] : null,
                'created_by' => $request->user()?->id,
            ]);

            // A recurring template carries no slots of its own; the definitions are
            // copied onto each generated occurrence with their datetimes shifted.
            // Recurring events can never be linked to a training request (enforced
            // in StoreEventRequest), so there is no training request to link here.
            if ($isRecurring) {
                $this->generateEventOccurrences->handle(
                    $event,
                    $validated['pilot_slots'] ?? [],
                    $validated['atc_slots'] ?? [],
                );

                return;
            }

            if (! empty($validated['pilot_slots'])) {
                $pilotSlots = array_map(fn (array $slot): array => [
                    'event_id' => $event->id,
                    'airline_icao' => $slot['airline_icao'],
                    'flight_number' => $slot['flight_number'],
                    'aircraft' => $slot['aircraft'],
                    'origin' => $slot['origin'],
                    'destination' => $slot['destination'],
                    'category' => $slot['category'],
                    'departs_at' => $slot['departs_at'],
                    'arrives_at' => $slot['arrives_at'] ?? null,
                    'gate' => $slot['gate'] ?? null,
                    'status' => SlotStatus::AVAILABLE,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $validated['pilot_slots']);
                $event->pilotSlots()->createMany($pilotSlots);
            }

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

            if (! empty($validated['training_request_id'])) {
                TrainingRequest::where('id', $validated['training_request_id'])
                    ->update(['event_id' => $event->id]);
            }
        });

        return $event;
    }

    private function storeFile(StoreEventRequest $request): ?string
    {
        $imageUrl = null;

        if ($request->hasFile('image')) {
            if (! ($path = $request->file('image')->store('events', 'public'))) {
                throw ValidationException::withMessages(['image' => 'Failed to upload image.']);
            }

            $imageUrl = Storage::disk('public')->url($path);
        }

        return $imageUrl;
    }
}
