<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Enums\EventStatus;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventsUpdateTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private function validPayload(Event $event): array
    {
        return [
            'name' => $event->name,
            'name_en' => $event->name_en ?? '',
            'description' => $event->description,
            'description_en' => $event->description_en ?? '',
            'type' => $event->type->value,
            'locations' => $event->locations,
            'starts_at' => '2026-06-01T18:00',
            'ends_at' => '2026-06-01T22:00',
            'tags' => [],
            'status' => EventStatus::ACTIVE->value,
            'pilot_slots_enabled' => false,
            'atc_slots_enabled' => false,
            'pilot_slots' => [],
            'atc_slots' => [],
        ];
    }

    #[Test]
    public function guests_are_redirected_from_update(): void
    {
        $event = Event::factory()->create();

        $this->put(route('dashboard.events.update', $event), $this->validPayload($event))
            ->assertRedirect(route('home'));
    }

    #[Test]
    public function unauthorized_users_cannot_update_events(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), $this->validPayload($event))
            ->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_update_event_details(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), array_merge($this->validPayload($event), [
                'name' => 'Evento Actualizado',
                'locations' => 'SEGU',
            ]))
            ->assertRedirect(route('dashboard.events.show', $event));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Evento Actualizado',
            'locations' => 'SEGU',
        ]);
    }

    #[Test]
    public function event_status_can_be_updated_to_cancelled(): void
    {
        $event = Event::factory()->create(['status' => EventStatus::ACTIVE]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), array_merge($this->validPayload($event), [
                'status' => EventStatus::CANCELLED->value,
            ]));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'status' => EventStatus::CANCELLED->value,
        ]);
    }

    #[Test]
    public function draft_and_finalized_statuses_are_rejected(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->director()->create();

        foreach ([EventStatus::DRAFT->value, EventStatus::FINALIZED->value] as $status) {
            $this->actingAs($user)
                ->put(route('dashboard.events.update', $event), array_merge($this->validPayload($event), [
                    'status' => $status,
                ]))
                ->assertSessionHasErrors('status');
        }
    }

    #[Test]
    public function atc_slots_are_replaced_when_no_reservations_exist(): void
    {
        $event = Event::factory()->create();
        AtcSlot::factory()->count(2)->create(['event_id' => $event->id, 'callsign' => 'OLD_APP']);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), array_merge($this->validPayload($event), [
                'atc_slots_enabled' => true,
                'atc_slots' => [
                    ['callsign' => 'SEQM_APP', 'starts_at' => '18:00', 'ends_at' => '19:00'],
                ],
            ]));

        $this->assertSoftDeleted('atc_slots', ['event_id' => $event->id, 'callsign' => 'OLD_APP']);
        $this->assertDatabaseHas('atc_slots', ['event_id' => $event->id, 'callsign' => 'SEQM_APP']);
    }

    #[Test]
    public function atc_slots_are_not_replaced_when_reservations_exist(): void
    {
        $event = Event::factory()->create();
        $reserved = AtcSlot::factory()->reserved()->create([
            'event_id' => $event->id,
            'callsign' => 'SEQM_CTR',
        ]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), array_merge($this->validPayload($event), [
                'atc_slots_enabled' => true,
                'atc_slots' => [
                    ['callsign' => 'NEW_APP', 'starts_at' => '20:00', 'ends_at' => '21:00'],
                ],
            ]));

        $this->assertDatabaseHas('atc_slots', ['id' => $reserved->id, 'callsign' => 'SEQM_CTR']);
        $this->assertDatabaseMissing('atc_slots', ['event_id' => $event->id, 'callsign' => 'NEW_APP']);
    }

    #[Test]
    public function pilot_slots_are_replaced_when_no_reservations_exist(): void
    {
        $event = Event::factory()->create();
        PilotSlot::factory()->count(2)->create(['event_id' => $event->id, 'airline_icao' => 'OLD', 'flight_number' => 'OLD001']);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), array_merge($this->validPayload($event), [
                'pilot_slots_enabled' => true,
                'pilot_slots' => [
                    [
                        'airline_icao' => 'ECA',
                        'flight_number' => 'ECA001',
                        'aircraft' => 'B738',
                        'origin' => 'SEQM',
                        'destination' => 'SEGU',
                        'departs_at' => '2026-06-01 18:00',
                        'gate' => 'A1',
                    ],
                ],
            ]));

        $this->assertSoftDeleted('pilot_slots', ['event_id' => $event->id, 'airline_icao' => 'OLD']);
        $this->assertDatabaseHas('pilot_slots', ['event_id' => $event->id, 'airline_icao' => 'ECA']);
    }

    #[Test]
    public function pilot_slots_are_not_replaced_when_reservations_exist(): void
    {
        $event = Event::factory()->create();
        $reserved = PilotSlot::factory()->reserved()->create([
            'event_id' => $event->id,
            'airline_icao' => 'ECA',
            'flight_number' => 'ECA999',
        ]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), array_merge($this->validPayload($event), [
                'pilot_slots_enabled' => true,
                'pilot_slots' => [
                    [
                        'airline_icao' => 'NEW',
                        'flight_number' => 'NEW001',
                        'aircraft' => 'A320',
                        'origin' => 'SEQM',
                        'destination' => 'SEGU',
                        'departs_at' => '2026-06-01 18:00',
                        'gate' => '',
                    ],
                ],
            ]));

        $this->assertDatabaseHas('pilot_slots', ['id' => $reserved->id, 'airline_icao' => 'ECA']);
        $this->assertDatabaseMissing('pilot_slots', ['event_id' => $event->id, 'airline_icao' => 'NEW']);
    }

    #[Test]
    public function image_is_replaced_when_new_file_is_uploaded(): void
    {
        Storage::fake('public');

        $event = Event::factory()->create(['image_url' => 'https://example.com/old.jpg']);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), array_merge($this->validPayload($event), [
                'image' => UploadedFile::fake()->image('new.jpg'),
            ]));

        $event->refresh();
        $this->assertNotNull($event->image_url);
        $this->assertStringContainsString('events/', $event->image_url);
    }

    #[Test]
    public function image_is_kept_when_no_new_file_is_uploaded(): void
    {
        $event = Event::factory()->create(['image_url' => 'https://example.com/existing.jpg']);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->put(route('dashboard.events.update', $event), $this->validPayload($event));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'image_url' => 'https://example.com/existing.jpg',
        ]);
    }
}
