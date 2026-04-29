<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Enums\SlotStatus;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use App\Models\UserOAuthToken;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReservationsDestroyTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_cancel(): void
    {
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->reserved()->create();

        $this->delete(route('dashboard.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertRedirect(route('home'));
    }

    #[Test]
    public function user_can_cancel_their_reserved_pilot_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertRedirect();

        $slot->refresh();
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->status);
        $this->assertNull($slot->pilot_id);
    }

    #[Test]
    public function user_can_cancel_their_confirmed_pilot_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->confirmed()->create(['pilot_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertRedirect();

        $slot->refresh();
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->status);
        $this->assertNull($slot->pilot_id);
    }

    #[Test]
    public function user_cannot_cancel_another_users_pilot_slot(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $otherUser->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertForbidden();

        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }

    #[Test]
    public function cannot_cancel_an_available_pilot_slot(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = PilotSlot::factory()->for($event)->create(['status' => SlotStatus::AVAILABLE, 'pilot_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.events.pilot-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertStatus(409);
    }

    #[Test]
    public function user_can_cancel_their_reserved_atc_slot(): void
    {
        Http::fake([
            '*/v2/atc/bookings/*' => Http::response([], 200),
        ]);

        $user = User::factory()->create();
        UserOAuthToken::factory()->for($user)->create();
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->reserved()->create([
            'atc_id' => $user->id,
            'ivao_booking' => ['id' => 999],
        ]);

        $this->actingAs($user)
            ->delete(route('dashboard.events.atc-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertRedirect();

        $slot->refresh();
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->status);
        $this->assertNull($slot->atc_id);
        $this->assertNull($slot->ivao_booking);
    }

    #[Test]
    public function user_cannot_cancel_another_users_atc_slot(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $otherUser->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.events.atc-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertForbidden();

        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }

    #[Test]
    public function atc_cancel_redirects_to_auth_when_ivao_returns_401(): void
    {
        Http::fake([
            '*/v2/atc/bookings/*' => Http::response([], 401),
        ]);

        $user = User::factory()->create();
        UserOAuthToken::factory()->for($user)->create();
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->reserved()->create([
            'atc_id' => $user->id,
            'ivao_booking' => ['id' => 999],
        ]);

        $this->actingAs($user)
            ->delete(route('dashboard.events.atc-slot.destroy', ['event' => $event->slug, 'slot' => $slot->id]))
            ->assertRedirect(route('auth.redirect'));

        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }
}
