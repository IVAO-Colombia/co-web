<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_events_index(): void
    {
        $this->get(route('events.index'))->assertRedirect(route('home'));
    }

    public function test_authenticated_users_can_visit_events_index(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('events.index'))->assertOk();
    }

    public function test_events_index_returns_paginated_events(): void
    {
        $this->actingAs(User::factory()->create());

        Event::factory()->count(3)->create();

        $this->get(route('events.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('events/Index')
                ->has('events.data', 3)
            );
    }

    public function test_events_index_filters_by_query(): void
    {
        $this->actingAs(User::factory()->create());

        Event::factory()->create(['name' => 'Aurora Cross Country']);
        Event::factory()->create(['name' => 'Something Else']);

        $this->get(route('events.index', ['query' => 'Aurora']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('events.data', 1)
                ->where('events.data.0.name', 'Aurora Cross Country')
            );
    }

    public function test_events_index_filters_by_status(): void
    {
        $this->actingAs(User::factory()->create());

        Event::factory()->create(['status' => EventStatus::ACTIVE]);
        Event::factory()->draft()->create();
        Event::factory()->cancelled()->create();

        $this->get(route('events.index', ['status' => 'draft']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('events.data', 1));
    }

    public function test_events_index_filters_by_type(): void
    {
        $this->actingAs(User::factory()->create());

        Event::factory()->create(['type' => EventType::EXAM]);
        Event::factory()->create(['type' => EventType::TRAINING]);

        $this->get(route('events.index', ['type' => 'exam']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('events.data', 1));
    }

    public function test_events_index_passes_active_filters_as_prop(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('events.index', ['query' => 'test', 'status' => 'active']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('filters.query', 'test')
                ->where('filters.status', 'active')
            );
    }
}
