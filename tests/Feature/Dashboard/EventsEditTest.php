<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventsEditTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_edit(): void
    {
        $event = Event::factory()->create();

        $this->get(route('dashboard.events.edit', $event))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function unauthorized_users_cannot_access_edit(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.edit', $event))
            ->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_access_edit_page(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.edit', $event))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableInertia => $page
                    ->has('event')
                    ->where('event.slug', $event->slug)
                    ->has('hasReservedPilotSlots')
                    ->has('hasReservedAtcSlots')
                    ->where('hasReservedPilotSlots', false)
                    ->where('hasReservedAtcSlots', false),
            );
    }

    #[Test]
    public function has_reserved_pilot_slots_is_true_when_slot_is_reserved(): void
    {
        $event = Event::factory()->create();
        PilotSlot::factory()->reserved()->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.edit', $event))
            ->assertInertia(
                fn (AssertableInertia $page): AssertableInertia => $page
                    ->where('hasReservedPilotSlots', true)
                    ->where('hasReservedAtcSlots', false),
            );
    }

    #[Test]
    public function has_reserved_atc_slots_is_true_when_slot_is_reserved(): void
    {
        $event = Event::factory()->create();
        AtcSlot::factory()->reserved()->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.edit', $event))
            ->assertInertia(
                fn (AssertableInertia $page): AssertableInertia => $page
                    ->where('hasReservedPilotSlots', false)
                    ->where('hasReservedAtcSlots', true),
            );
    }

    #[Test]
    public function edit_page_includes_atc_and_pilot_slots(): void
    {
        $event = Event::factory()->create();
        AtcSlot::factory()->create(['event_id' => $event->id, 'callsign' => 'SEQM_APP']);
        PilotSlot::factory()->create(['event_id' => $event->id, 'airline_icao' => 'ECA', 'flight_number' => '001']);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.edit', $event))
            ->assertInertia(
                fn (AssertableInertia $page): AssertableInertia => $page
                    ->has('event.atc_slots', 1)
                    ->has('event.pilot_slots', 1)
                    ->where('event.atc_slots.0.callsign', 'SEQM_APP')
                    ->where('event.pilot_slots.0.airline_icao', 'ECA'),
            );
    }
}
