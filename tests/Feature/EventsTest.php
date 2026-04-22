<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Models\Event;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventsTest extends TestCase
{
    #[Test]
    public function guests_can_visit_public_events_landing_page(): void
    {
        Event::factory()->count(2)->create();

        $this->get(route('home.events'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('events/landing/Events')
                ->has('events', 2)
            );
    }

    #[Test]
    public function guests_are_redirected_from_events_index(): void
    {
        $this->get(route('dashboard.events.index'))->assertRedirect(route('home'));
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
                ->component('events/Index')
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
}
