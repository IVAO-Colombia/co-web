<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventsShowTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_events_show(): void
    {
        $event = Event::factory()->create();

        $this->get(route('dashboard.events.show', $event))
            ->assertRedirect(route('home'));
    }

    #[Test]
    public function unauthorized_users_cannot_view_event_show(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.show', $event))
            ->assertForbidden();
    }

    #[Test]
    public function authorized_users_can_view_event_show(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.show', $event))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('events/Show')
                ->has('event')
                ->where('event.slug', $event->slug)
            );
    }

    #[Test]
    public function event_show_returns_404_for_unknown_slug(): void
    {
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.show', 'non-existent-slug'))
            ->assertNotFound();
    }

    #[Test]
    public function event_show_includes_pilot_slots(): void
    {
        $event = Event::factory()->create(['pilot_slots_enabled' => true]);
        PilotSlot::factory()->count(3)->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.show', $event))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('events/Show')
                ->has('event.pilot_slots', 3)
            );
    }

    #[Test]
    public function event_show_includes_atc_slots(): void
    {
        $event = Event::factory()->create(['atc_slots_enabled' => true]);
        AtcSlot::factory()->count(2)->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.show', $event))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('events/Show')
                ->has('event.atc_slots', 2)
            );
    }
}
