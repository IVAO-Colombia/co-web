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

class EventsDestroyTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_events_destroy(): void
    {
        $event = Event::factory()->create();

        $this->delete(route('dashboard.events.destroy', $event))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function unauthorized_users_cannot_delete_events(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.events.destroy', $event))
            ->assertForbidden();
    }

    #[Test]
    public function cannot_delete_event_with_reserved_pilot_slot(): void
    {
        $event = Event::factory()->create();
        PilotSlot::factory()->reserved()->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.events.destroy', $event))
            ->assertSessionHasErrors('event');

        $this->assertNotSoftDeleted($event);
    }

    #[Test]
    public function cannot_delete_event_with_reserved_atc_slot(): void
    {
        $event = Event::factory()->create();
        AtcSlot::factory()->reserved()->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.events.destroy', $event))
            ->assertSessionHasErrors('event');

        $this->assertNotSoftDeleted($event);
    }

    #[Test]
    public function can_delete_event_with_no_slots(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.events.destroy', $event))
            ->assertRedirect(route('dashboard.events.index'));

        $this->assertSoftDeleted($event);
    }

    #[Test]
    public function can_delete_event_with_available_slots(): void
    {
        $event = Event::factory()->create();
        $pilotSlot = PilotSlot::factory()->create(['event_id' => $event->id, 'status' => SlotStatus::AVAILABLE]);
        $atcSlot = AtcSlot::factory()->create(['event_id' => $event->id, 'status' => SlotStatus::AVAILABLE]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.events.destroy', $event))
            ->assertRedirect(route('dashboard.events.index'));

        $this->assertSoftDeleted($event);
        $this->assertSoftDeleted($pilotSlot);
        $this->assertSoftDeleted($atcSlot);
    }

    #[Test]
    public function slots_are_not_deleted_when_event_has_reserved_slots(): void
    {
        $event = Event::factory()->create();
        $availableSlot = PilotSlot::factory()->create(['event_id' => $event->id]);
        PilotSlot::factory()->reserved()->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.events.destroy', $event))
            ->assertSessionHasErrors('event');

        $this->assertNotSoftDeleted($availableSlot);
    }

    #[Test]
    public function deleting_a_recurring_template_deletes_the_whole_series(): void
    {
        $template = Event::factory()->recurring()->create();
        $occurrenceOne = Event::factory()->occurrenceOf($template)->create();
        $occurrenceTwo = Event::factory()->occurrenceOf($template)->create();
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.events.destroy', $template))
            ->assertRedirect(route('dashboard.events.index'));

        $this->assertSoftDeleted($template);
        $this->assertSoftDeleted($occurrenceOne);
        $this->assertSoftDeleted($occurrenceTwo);
    }

    #[Test]
    public function cannot_delete_a_series_when_an_occurrence_has_a_reserved_slot(): void
    {
        $template = Event::factory()->recurring()->create();
        $occurrence = Event::factory()->occurrenceOf($template)->create();
        PilotSlot::factory()->reserved()->create(['event_id' => $occurrence->id]);
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.events.destroy', $template))
            ->assertSessionHasErrors('event');

        $this->assertNotSoftDeleted($template);
        $this->assertNotSoftDeleted($occurrence);
    }
}
