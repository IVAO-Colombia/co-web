<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Enums\EventStatus;
use App\Models\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FinalizeEndedEventsTest extends TestCase
{
    #[Test]
    public function it_finalizes_an_event_whose_end_date_has_passed(): void
    {
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subHour(),
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::FINALIZED, $event->fresh()->status);
    }

    #[Test]
    public function it_leaves_an_event_whose_end_date_is_in_the_future(): void
    {
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::ACTIVE, $event->fresh()->status);
    }

    #[Test]
    public function it_finalizes_a_null_end_date_event_that_started_before_today(): void
    {
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subDay(),
            'ends_at' => null,
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::FINALIZED, $event->fresh()->status);
    }

    #[Test]
    public function it_leaves_a_null_end_date_event_that_started_earlier_today(): void
    {
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->startOfDay()->addHour(),
            'ends_at' => null,
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::ACTIVE, $event->fresh()->status);
    }

    #[Test]
    public function it_leaves_a_cancelled_past_event_as_cancelled(): void
    {
        $event = Event::factory()->cancelled()->create([
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subHour(),
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::CANCELLED, $event->fresh()->status);
    }

    #[Test]
    public function it_leaves_a_draft_past_event_as_draft(): void
    {
        $event = Event::factory()->draft()->create([
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subHour(),
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::DRAFT, $event->fresh()->status);
    }

    #[Test]
    public function it_leaves_a_recurring_template_active_while_an_occurrence_is_still_active(): void
    {
        $template = Event::factory()->recurring()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subWeek(),
        ]);
        Event::factory()->occurrenceOf($template)->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->subHour(),
        ]);
        Event::factory()->occurrenceOf($template)->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDays(2),
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::ACTIVE, $template->fresh()->status);
    }

    #[Test]
    public function it_finalizes_a_recurring_template_once_all_its_occurrences_have_ended(): void
    {
        $template = Event::factory()->recurring()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subWeeks(2),
        ]);
        $stillActiveOccurrence = Event::factory()->occurrenceOf($template)->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subHour(),
        ]);
        Event::factory()->occurrenceOf($template)->cancelled()->create([
            'starts_at' => now()->subWeek(),
            'ends_at' => now()->subWeek()->addHour(),
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::FINALIZED, $template->fresh()->status);
        $this->assertEquals(EventStatus::FINALIZED, $stillActiveOccurrence->fresh()->status);
    }

    #[Test]
    public function it_leaves_a_recurring_template_with_no_occurrences_alone(): void
    {
        $template = Event::factory()->recurring()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subWeek(),
        ]);

        $this->artisan('events:finalize-ended')->assertSuccessful();

        $this->assertEquals(EventStatus::ACTIVE, $template->fresh()->status);
    }
}
