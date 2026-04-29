<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Enums\SlotStatus;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReservationsUpdateTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_confirm(): void
    {
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->reserved()->create();

        $this->patch(route('dashboard.events.atc-slot.update', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertRedirect(route('home'));
    }

    #[Test]
    public function user_can_confirm_their_reserved_atc_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $user->id]);

        $this->actingAs($user)
            ->patch(route('dashboard.events.atc-slot.update', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertRedirect();

        $this->assertEquals(SlotStatus::CONFIRMED, $slot->fresh()->status);
    }

    #[Test]
    public function user_can_confirm_their_reserved_pilot_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);

        $this->actingAs($user)
            ->patch(route('dashboard.events.pilot-slot.update', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertRedirect();

        $this->assertEquals(SlotStatus::CONFIRMED, $slot->fresh()->status);
    }

    #[Test]
    public function user_cannot_confirm_another_users_atc_slot(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $otherUser->id]);

        $this->actingAs($user)
            ->patch(route('dashboard.events.atc-slot.update', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertForbidden();

        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }

    #[Test]
    public function user_cannot_confirm_another_users_pilot_slot(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $otherUser->id]);

        $this->actingAs($user)
            ->patch(route('dashboard.events.pilot-slot.update', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertForbidden();

        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }

    #[Test]
    public function cannot_confirm_an_available_atc_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->create(['status' => SlotStatus::AVAILABLE, 'atc_id' => $user->id]);

        $this->actingAs($user)
            ->patch(route('dashboard.events.atc-slot.update', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertStatus(409);

        $this->assertEquals(SlotStatus::AVAILABLE, $slot->fresh()->status);
    }

    #[Test]
    public function cannot_confirm_an_already_confirmed_atc_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->confirmed()->create(['atc_id' => $user->id]);

        $this->actingAs($user)
            ->patch(route('dashboard.events.atc-slot.update', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertStatus(409);

        $this->assertEquals(SlotStatus::CONFIRMED, $slot->fresh()->status);
    }
}
