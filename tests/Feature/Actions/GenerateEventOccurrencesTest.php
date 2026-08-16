<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\Events\GenerateEventOccurrences;
use App\Models\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GenerateEventOccurrencesTest extends TestCase
{
    #[Test]
    public function it_generates_one_occurrence_per_matching_date(): void
    {
        // 2026-06-01 is a Monday; weekly on Mondays through 2026-06-15 => 3 occurrences.
        $template = Event::factory()->create([
            'starts_at' => '2026-06-01 18:00',
            'ends_at' => '2026-06-01 20:00',
            'is_recurring' => true,
            'recurrence_interval' => 1,
            'recurrence_weekdays' => [1],
            'recurrence_ends_at' => '2026-06-15',
        ]);

        app(GenerateEventOccurrences::class)->handle($template);

        $occurrences = $template->occurrences()->orderBy('starts_at')->get();

        $this->assertCount(3, $occurrences);
        $this->assertSame(
            ['2026-06-01 18:00', '2026-06-08 18:00', '2026-06-15 18:00'],
            $occurrences->map(fn (Event $o): string => $o->starts_at->format('Y-m-d H:i'))->all(),
        );
        $this->assertSame(
            ['2026-06-01 20:00', '2026-06-08 20:00', '2026-06-15 20:00'],
            $occurrences->map(fn (Event $o): string => $o->ends_at->format('Y-m-d H:i'))->all(),
        );
    }

    #[Test]
    public function it_shifts_slot_times_to_each_occurrence_date(): void
    {
        $template = Event::factory()->create([
            'starts_at' => '2026-06-01 18:00',
            'ends_at' => '2026-06-01 20:00',
            'pilot_slots_enabled' => true,
            'atc_slots_enabled' => true,
            'is_recurring' => true,
            'recurrence_interval' => 1,
            'recurrence_weekdays' => [1],
            'recurrence_ends_at' => '2026-06-08',
        ]);

        app(GenerateEventOccurrences::class)->handle(
            $template,
            pilotSlots: [[
                'airline_icao' => 'AVA',
                'flight_number' => '001',
                'aircraft' => 'A320',
                'origin' => 'SEQM',
                'destination' => 'SEGU',
                'departs_at' => '2026-06-01 18:30',
                'gate' => 'B12',
            ]],
            atcSlots: [[
                'callsign' => 'SEQM_APP',
                'starts_at' => '2026-06-01 18:00',
                'ends_at' => '2026-06-01 20:00',
            ]],
        );

        $secondWeek = $template->occurrences()->orderBy('starts_at')->get()->last();

        $this->assertSame('2026-06-08 18:00', $secondWeek->starts_at->format('Y-m-d H:i'));
        $this->assertSame(
            '2026-06-08 18:30',
            $secondWeek->pilotSlots->first()->departs_at->format('Y-m-d H:i'),
        );
        $this->assertSame(
            '2026-06-08 18:00',
            $secondWeek->atcSlots->first()->starts_at->format('Y-m-d H:i'),
        );
        $this->assertSame(
            '2026-06-08 20:00',
            $secondWeek->atcSlots->first()->ends_at->format('Y-m-d H:i'),
        );

        // The template itself never carries slots.
        $this->assertCount(0, $template->pilotSlots);
        $this->assertCount(0, $template->atcSlots);
    }
}
