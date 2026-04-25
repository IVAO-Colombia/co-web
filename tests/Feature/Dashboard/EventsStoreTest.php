<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Models\Event;
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
            ->assertRedirect(route('home'));
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
            'status' => EventStatus::DRAFT->value,
            'created_by' => $user->id,
        ]);
    }

    #[Test]
    public function event_is_always_created_as_draft(): void
    {
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->post(route('dashboard.events.store'), $this->validPayload());

        $this->assertDatabaseHas('events', ['status' => EventStatus::DRAFT->value]);
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
