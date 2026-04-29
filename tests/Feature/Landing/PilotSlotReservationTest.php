<?php

declare(strict_types=1);

namespace Tests\Feature\Landing;

use App\Enums\SlotStatus;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PilotSlotReservationTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Reservation tests
    // -------------------------------------------------------------------------

    #[Test]
    public function authenticated_user_can_reserve_an_available_pilot_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->create(['status' => SlotStatus::AVAILABLE]);

        $response = $this->actingAs($user)
            ->post(route('home.events.pilot-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $slot->refresh();
        $this->assertEquals(SlotStatus::RESERVED, $slot->status);
        $this->assertEquals($user->id, $slot->pilot_id);
    }

    #[Test]
    public function unauthenticated_user_is_redirected_to_home(): void
    {
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->create(['status' => SlotStatus::AVAILABLE]);

        $response = $this->post(route('home.events.pilot-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('home'));
    }

    #[Test]
    public function returns_404_when_slot_does_not_belong_to_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $otherEvent = Event::factory()->create();
        $slot = PilotSlot::factory()->for($otherEvent)->create(['status' => SlotStatus::AVAILABLE]);

        $response = $this->actingAs($user)
            ->post(route('home.events.pilot-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertNotFound();
    }

    #[Test]
    public function returns_409_when_slot_is_already_reserved(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->reserved()->create();

        $response = $this->actingAs($user)
            ->post(route('home.events.pilot-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertStatus(409);
    }

    // -------------------------------------------------------------------------
    // Cancellation tests
    // -------------------------------------------------------------------------

    #[Test]
    public function authenticated_user_can_cancel_their_pilot_slot_reservation(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete(route('home.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('home.events.show', $event));
        $response->assertSessionHas('success');

        $slot->refresh();
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->status);
        $this->assertNull($slot->pilot_id);
    }

    #[Test]
    public function returns_403_when_user_tries_to_cancel_another_users_reservation(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $otherUser->id]);

        $response = $this->actingAs($user)
            ->delete(route('home.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertForbidden();
        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }

    #[Test]
    public function returns_409_when_trying_to_cancel_an_available_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->create(['status' => SlotStatus::AVAILABLE]);

        $response = $this->actingAs($user)
            ->delete(route('home.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertStatus(409);
    }

    #[Test]
    public function returns_404_on_cancel_when_slot_does_not_belong_to_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $otherEvent = Event::factory()->create();
        $slot = PilotSlot::factory()->for($otherEvent)->reserved()->create(['pilot_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete(route('home.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertNotFound();
    }
}
