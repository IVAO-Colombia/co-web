<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\SlotStatus;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventsDestroyTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_events_destroy(): void
    {
        $event = Event::factory()->create();

        $this->delete(route('events.destroy', $event))
            ->assertRedirect(route('home'));
    }

    #[Test]
    public function unauthorized_users_cannot_delete_events(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete(route('events.destroy', $event))
            ->assertForbidden();
    }

    #[Test]
    public function cannot_delete_event_with_reserved_pilot_slot(): void
    {
        $event = Event::factory()->create();
        PilotSlot::factory()->unavailable()->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('events.destroy', $event))
            ->assertSessionHasErrors('event');

        $this->assertNotSoftDeleted($event);
    }

    #[Test]
    public function cannot_delete_event_with_reserved_atc_slot(): void
    {
        $event = Event::factory()->create();
        AtcSlot::factory()->unavailable()->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('events.destroy', $event))
            ->assertSessionHasErrors('event');

        $this->assertNotSoftDeleted($event);
    }

    #[Test]
    public function can_delete_event_with_no_slots(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('events.destroy', $event))
            ->assertRedirect(route('events.index'));

        $this->assertSoftDeleted($event);
    }

    #[Test]
    public function can_delete_event_with_available_and_cancelled_slots(): void
    {
        $event = Event::factory()->create();
        $pilotSlot = PilotSlot::factory()->create(['event_id' => $event->id, 'status' => SlotStatus::AVAILABLE]);
        $cancelledPilotSlot = PilotSlot::factory()->cancelled()->create(['event_id' => $event->id]);
        $atcSlot = AtcSlot::factory()->create(['event_id' => $event->id, 'status' => SlotStatus::AVAILABLE]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('events.destroy', $event))
            ->assertRedirect(route('events.index'));

        $this->assertSoftDeleted($event);
        $this->assertSoftDeleted($pilotSlot);
        $this->assertSoftDeleted($cancelledPilotSlot);
        $this->assertSoftDeleted($atcSlot);
    }

    #[Test]
    public function slots_are_not_deleted_when_event_has_reserved_slots(): void
    {
        $event = Event::factory()->create();
        $availableSlot = PilotSlot::factory()->create(['event_id' => $event->id]);
        PilotSlot::factory()->unavailable()->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('events.destroy', $event))
            ->assertSessionHasErrors('event');

        $this->assertNotSoftDeleted($availableSlot);
    }
}
