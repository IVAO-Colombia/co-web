<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LandingEventsTest extends TestCase
{
    #[Test]
    public function guests_can_visit_public_events_landing_page(): void
    {
        Event::factory()->count(2)->create(['starts_at' => now()->addDays(1)]);

        $this->get(route('home.events'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('landing/events/Index')
                ->has('events', 2)
            );
    }

    #[Test]
    public function guests_are_redirected_from_events_index(): void
    {
        $this->get(route('dashboard.events.index'))->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function authenticated_users_can_visit_events_index(): void
    {
        $this->actingAs(User::factory()->director()->create());

        $this->get(route('dashboard.events.index'))->assertOk();
    }

    #[Test]
    public function events_index_returns_paginated_events(): void
    {
        $this->actingAs(User::factory()->director()->create());

        Event::factory()->count(3)->create();

        $this->get(route('dashboard.events.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('dashboard/events/Index')
                ->has('events.data', 3)
            );
    }

    #[Test]
    public function events_index_filters_by_query(): void
    {
        $this->actingAs(User::factory()->director()->create());

        Event::factory()->create(['name' => 'Aurora Cross Country']);
        Event::factory()->create(['name' => 'Something Else']);

        $this->get(route('dashboard.events.index', ['query' => 'Aurora']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('events.data', 1)
                ->where('events.data.0.name', 'Aurora Cross Country')
            );
    }

    #[Test]
    public function events_index_filters_by_status(): void
    {
        $this->actingAs(User::factory()->director()->create());

        Event::factory()->create(['status' => EventStatus::ACTIVE]);
        Event::factory()->draft()->create();
        Event::factory()->cancelled()->create();

        $this->get(route('dashboard.events.index', ['status' => 'draft']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('events.data', 1));
    }

    #[Test]
    public function events_index_filters_by_type(): void
    {
        $this->actingAs(User::factory()->director()->create());

        Event::factory()->create(['type' => EventType::EXAM]);
        Event::factory()->create(['type' => EventType::TRAINING]);

        $this->get(route('dashboard.events.index', ['type' => 'exam']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('events.data', 1));
    }

    #[Test]
    public function events_index_passes_active_filters_as_prop(): void
    {
        $this->actingAs(User::factory()->director()->create());

        $this->get(route('dashboard.events.index', ['query' => 'test', 'status' => 'active']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('filters.query', 'test')
                ->where('filters.status', 'active')
            );
    }

    #[Test]
    public function guests_can_view_public_event_show(): void
    {
        $this->withoutVite();

        $event = Event::factory()->create();

        $this->get(route('home.events.show', $event))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('landing/events/Show', false)
                ->has('event')
                ->where('event.slug', $event->slug)
            );
    }

    #[Test]
    public function event_show_returns_404_for_unknown_slug(): void
    {
        $this->get(route('home.events.show', 'non-existent-slug'))
            ->assertNotFound();
    }

    #[Test]
    public function guests_cannot_see_slot_status_or_reservation(): void
    {
        $this->withoutVite();

        $event = Event::factory()->create();
        PilotSlot::factory()->reserved()->create(['event_id' => $event->id]);
        AtcSlot::factory()->reserved()->create(['event_id' => $event->id]);

        $this->get(route('home.events.show', $event))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('event.pilot_slots', 1)
                ->missing('event.pilot_slots.0.status')
                ->missing('event.pilot_slots.0.pilot_id')
                ->has('event.atc_slots', 1)
                ->missing('event.atc_slots.0.status')
                ->missing('event.atc_slots.0.atc_id')
            );
    }

    #[Test]
    public function authenticated_users_can_see_slot_status_and_reservation(): void
    {
        $this->withoutVite();

        $pilot = User::factory()->create();
        $event = Event::factory()->create();
        PilotSlot::factory()->reserved()->create(['event_id' => $event->id, 'pilot_id' => $pilot->id]);

        $atc = User::factory()->create();
        AtcSlot::factory()->reserved()->create(['event_id' => $event->id, 'atc_id' => $atc->id]);

        $this->actingAs(User::factory()->create())
            ->get(route('home.events.show', $event))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('event.pilot_slots', 1)
                ->where('event.pilot_slots.0.status', 'reserved')
                ->has('event.atc_slots', 1)
                ->where('event.atc_slots.0.status', 'reserved')
            );
    }
}
