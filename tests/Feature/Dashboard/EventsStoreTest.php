<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Models\Event;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventsStoreTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private function validPayload(): array
    {
        return [
            'name' => 'Evento de Prueba',
            'name_en' => 'Test Event',
            'description' => 'Descripción del evento',
            'description_en' => 'Event description',
            'type' => EventType::ONLINE_DAY->value,
            'locations' => 'SEQM',
            'starts_at' => '2026-06-01 18:00',
        ];
    }

    #[Test]
    public function guests_are_redirected_from_store(): void
    {
        $this->post(route('dashboard.events.store'), $this->validPayload())
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function unauthorized_users_cannot_store_events(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $this->validPayload())
            ->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_create_event(): void
    {
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $this->validPayload())
            ->assertRedirect(route('dashboard.events.index'));

        $this->assertDatabaseHas('events', [
            'name' => 'Evento de Prueba',
            'name_en' => 'Test Event',
            'type' => EventType::ONLINE_DAY->value,
            'locations' => 'SEQM',
            'status' => EventStatus::ACTIVE->value,
            'created_by' => $user->id,
        ]);
    }

    #[Test]
    public function event_can_be_created_with_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->director()->create();

        $this->actingAs($user)->post(route('dashboard.events.store'), array_merge(
            $this->validPayload(),
            ['image' => UploadedFile::fake()->image('banner.jpg', 800, 400)],
        ));

        $event = Event::first();
        $this->assertNotNull($event->image_url);
        Storage::disk('public')->assertExists('events/'.basename($event->image_url));
    }

    #[Test]
    public function event_can_be_created_with_tags(): void
    {
        $user = User::factory()->director()->create();

        $this->actingAs($user)->post(route('dashboard.events.store'), array_merge(
            $this->validPayload(),
            ['tags' => ['vfr', 'cross-country']],
        ));

        $event = Event::first();
        $this->assertContains('vfr', $event->tags);
        $this->assertContains('cross-country', $event->tags);
    }

    #[Test]
    public function event_can_be_created_with_pilot_slots(): void
    {
        $user = User::factory()->director()->create();

        $payload = array_merge($this->validPayload(), [
            'pilot_slots_enabled' => true,
            'pilot_slots' => [
                [
                    'airline_icao' => 'AVA',
                    'flight_number' => '001',
                    'aircraft' => 'A320',
                    'origin' => 'SEQM',
                    'destination' => 'SEGU',
                    'departs_at' => '2026-06-01 18:00',
                    'gate' => 'B12',
                ],
                [
                    'airline_icao' => 'AVA',
                    'flight_number' => '002',
                    'aircraft' => 'B738',
                    'origin' => 'SEGU',
                    'destination' => 'SEQM',
                    'departs_at' => '2026-06-01 20:00',
                    'gate' => null,
                ],
            ],
        ]);

        $this->actingAs($user)->post(route('dashboard.events.store'), $payload)
            ->assertRedirect(route('dashboard.events.index'));

        $event = Event::first();
        $this->assertCount(2, $event->pilotSlots);
        $this->assertTrue($event->pilot_slots_enabled);

        $this->assertDatabaseHas('pilot_slots', [
            'event_id' => $event->id,
            'airline_icao' => 'AVA',
            'aircraft' => 'A320',
            'origin' => 'SEQM',
            'destination' => 'SEGU',
        ]);
    }

    #[Test]
    public function event_can_be_created_with_atc_slots(): void
    {
        $user = User::factory()->director()->create();

        $startDate1 = now();
        $endDate1 = now()->addHour();
        $payload = array_merge($this->validPayload(), [
            'atc_slots_enabled' => true,
            'atc_slots' => [
                [
                    'callsign' => 'SEQM_APP',
                    'starts_at' => $startDate1->format('Y-m-d H:i'),
                    'ends_at' => $endDate1->format('Y-m-d H:i'),
                ],
                [
                    'callsign' => 'SEQM_TWR',
                    'starts_at' => $startDate1->format('Y-m-d H:i'),
                    'ends_at' => $endDate1->addHour()->format('Y-m-d H:i'),
                ],
            ],
        ]);

        $this->actingAs($user)->post(route('dashboard.events.store'), $payload)
            ->assertRedirect(route('dashboard.events.index'));

        $event = Event::first();
        $this->assertCount(2, $event->atcSlots);
        $this->assertTrue($event->atc_slots_enabled);

        $this->assertDatabaseHas('atc_slots', [
            'event_id' => $event->id,
            'callsign' => 'SEQM_APP',
            'starts_at' => $startDate1->startOfMinute()->toDateTimeString(),
            'ends_at' => $endDate1->startOfMinute()->toDateTimeString(),
        ]);
    }

    #[Test]
    public function authorized_user_can_create_recurring_event(): void
    {
        $user = User::factory()->director()->create();

        // 2026-06-01 is a Monday; weekly on Mondays through 2026-06-29 => 5 occurrences.
        $payload = array_merge($this->validPayload(), [
            'starts_at' => '2026-06-01 18:00',
            'is_recurring' => true,
            'recurrence_interval' => 1,
            'recurrence_weekdays' => [1],
            'recurrence_ends_at' => '2026-06-29',
        ]);

        $this->actingAs($user)->post(route('dashboard.events.store'), $payload)
            ->assertRedirect(route('dashboard.events.index'));

        $template = Event::where('is_recurring', true)->firstOrFail();
        $this->assertNull($template->parent_event_id);
        $this->assertSame([1], $template->recurrence_weekdays);

        $occurrences = $template->occurrences()->orderBy('starts_at')->get();
        $this->assertCount(5, $occurrences);
        $occurrences->each(fn (Event $occurrence) => $this->assertFalse($occurrence->is_recurring));
        $this->assertSame(
            ['2026-06-01 18:00', '2026-06-08 18:00', '2026-06-15 18:00', '2026-06-22 18:00', '2026-06-29 18:00'],
            $occurrences->map(fn (Event $occurrence): string => $occurrence->starts_at->format('Y-m-d H:i'))->all(),
        );
    }

    #[Test]
    public function recurring_event_with_interval_skips_weeks(): void
    {
        $user = User::factory()->director()->create();

        // Every 2 weeks on Mondays from 2026-06-01 through 2026-06-29 => Jun 1, 15, 29.
        $payload = array_merge($this->validPayload(), [
            'starts_at' => '2026-06-01 18:00',
            'is_recurring' => true,
            'recurrence_interval' => 2,
            'recurrence_weekdays' => [1],
            'recurrence_ends_at' => '2026-06-29',
        ]);

        $this->actingAs($user)->post(route('dashboard.events.store'), $payload);

        $template = Event::where('is_recurring', true)->firstOrFail();
        $this->assertSame(
            ['2026-06-01 18:00', '2026-06-15 18:00', '2026-06-29 18:00'],
            $template->occurrences()->orderBy('starts_at')->get()
                ->map(fn (Event $occurrence): string => $occurrence->starts_at->format('Y-m-d H:i'))->all(),
        );
    }

    #[Test]
    public function recurring_event_with_an_image_stores_weekdays_as_integers(): void
    {
        Storage::fake('public');

        $user = User::factory()->director()->create();

        // Attaching an image makes Inertia submit as multipart/form-data, which
        // stringifies every scalar. The weekdays must still land in the database
        // as integers, or the strict comparison in GenerateEventOccurrences
        // matches nothing and the series comes out empty.
        $payload = array_merge($this->validPayload(), [
            'starts_at' => '2026-06-01 18:00',
            'image' => UploadedFile::fake()->image('banner.jpg', 800, 400),
            'is_recurring' => '1',
            'recurrence_interval' => '1',
            'recurrence_weekdays' => ['1'],
            'recurrence_ends_at' => '2026-06-29',
        ]);

        $this->actingAs($user)->post(route('dashboard.events.store'), $payload)
            ->assertRedirect(route('dashboard.events.index'));

        $template = Event::where('is_recurring', true)->firstOrFail();
        $this->assertSame([1], $template->recurrence_weekdays);
        $this->assertCount(5, $template->occurrences);
    }

    #[Test]
    public function recurring_event_rejects_non_numeric_weekdays(): void
    {
        $user = User::factory()->director()->create();

        $payload = array_merge($this->validPayload(), [
            'starts_at' => '2026-06-01 18:00',
            'is_recurring' => true,
            'recurrence_interval' => 1,
            'recurrence_weekdays' => ['monday'],
            'recurrence_ends_at' => '2026-06-29',
        ]);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('recurrence_weekdays.0');
    }

    #[Test]
    public function recurring_event_requires_recurrence_fields(): void
    {
        $user = User::factory()->director()->create();
        $payload = array_merge($this->validPayload(), ['is_recurring' => true]);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors(['recurrence_interval', 'recurrence_weekdays', 'recurrence_ends_at']);
    }

    #[Test]
    public function non_recurring_event_ignores_empty_recurrence_fields(): void
    {
        $user = User::factory()->director()->create();

        // Mirrors the payload the frontend sends when the recurrence switch is
        // off: is_recurring is false, but the still-present default form state
        // includes an empty recurrence_weekdays array and a blank
        // recurrence_ends_at, which must not trigger validation errors.
        $payload = array_merge($this->validPayload(), [
            'is_recurring' => false,
            'recurrence_interval' => 1,
            'recurrence_weekdays' => [],
            'recurrence_ends_at' => '',
        ]);

        $this->actingAs($user)->post(route('dashboard.events.store'), $payload)
            ->assertRedirect(route('dashboard.events.index'));
    }

    #[Test]
    public function recurrence_end_date_must_be_after_start(): void
    {
        $user = User::factory()->director()->create();
        $payload = array_merge($this->validPayload(), [
            'starts_at' => '2026-06-01 18:00',
            'is_recurring' => true,
            'recurrence_interval' => 1,
            'recurrence_weekdays' => [1],
            'recurrence_ends_at' => '2026-05-01',
        ]);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('recurrence_ends_at');
    }

    #[Test]
    public function recurrence_is_prohibited_when_linked_to_a_training_request(): void
    {
        $user = User::factory()->director()->create();
        $trainingRequest = TrainingRequest::factory()->create();

        $payload = array_merge($this->validPayload(), [
            'starts_at' => '2026-06-01 18:00',
            'is_recurring' => true,
            'recurrence_interval' => 1,
            'recurrence_weekdays' => [1],
            'recurrence_ends_at' => '2026-06-29',
            'training_request_id' => $trainingRequest->id,
        ]);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('is_recurring');
    }

    #[Test]
    public function event_can_be_created_from_a_training_request(): void
    {
        $user = User::factory()->director()->create();
        $trainingRequest = TrainingRequest::factory()->create();

        // Mirrors the exact payload the frontend sends from a training request:
        // the recurrence card is hidden, but the default form state still
        // carries is_recurring => false, which must not trip the prohibited
        // rule that only targets recurring events.
        $payload = array_merge($this->validPayload(), [
            'is_recurring' => false,
            'recurrence_interval' => 1,
            'recurrence_weekdays' => [],
            'recurrence_ends_at' => '',
            'training_request_id' => $trainingRequest->id,
        ]);

        $this->actingAs($user)->post(route('dashboard.events.store'), $payload)
            ->assertRedirect(route('dashboard.events.index'));

        $this->assertNotNull($trainingRequest->refresh()->event_id);
    }

    #[Test]
    public function store_requires_name(): void
    {
        $user = User::factory()->director()->create();
        $payload = array_merge($this->validPayload(), ['name' => '']);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('name');
    }

    #[Test]
    public function store_requires_description(): void
    {
        $user = User::factory()->director()->create();
        $payload = array_merge($this->validPayload(), ['description' => '']);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('description');
    }

    #[Test]
    public function store_requires_valid_event_type(): void
    {
        $user = User::factory()->director()->create();
        $payload = array_merge($this->validPayload(), ['type' => 'invalid_type']);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('type');
    }

    #[Test]
    public function store_rejects_invalid_tags(): void
    {
        $user = User::factory()->director()->create();
        $payload = array_merge($this->validPayload(), ['tags' => ['not-a-valid-tag']]);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('tags.0');
    }

    #[Test]
    public function store_requires_starts_at(): void
    {
        $user = User::factory()->director()->create();
        $payload = array_merge($this->validPayload(), ['starts_at' => '']);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('starts_at');
    }

    #[Test]
    public function ends_at_must_be_after_starts_at(): void
    {
        $user = User::factory()->director()->create();
        $payload = array_merge($this->validPayload(), [
            'starts_at' => '2026-06-01 20:00:00',
            'ends_at' => '2026-06-01 18:00:00',
        ]);

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $payload)
            ->assertSessionHasErrors('ends_at');
    }

    #[Test]
    public function image_must_be_a_valid_image_type(): void
    {
        Storage::fake('public');
        $user = User::factory()->director()->create();

        $this->actingAs($user)->post(route('dashboard.events.store'), array_merge(
            $this->validPayload(),
            ['image' => UploadedFile::fake()->create('document.pdf', 100)],
        ))->assertSessionHasErrors('image');
    }
}
