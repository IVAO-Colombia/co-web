<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PilotSlotsReportTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_the_report(): void
    {
        $event = Event::factory()->create();

        $this->get(route('dashboard.events.pilot-slots.report', $event))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function unauthorized_users_cannot_download_the_report(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.events.pilot-slots.report', $event))
            ->assertForbidden();
    }

    #[Test]
    public function report_contains_only_reserved_and_confirmed_slots_with_the_right_columns(): void
    {
        $event = Event::factory()->create(['name' => 'Fly-In 2026', 'pilot_slots_enabled' => true]);
        $pilot = User::factory()->create(['vid' => 123456]);

        PilotSlot::factory()->reserved()->departure()->create([
            'event_id' => $event->id,
            'pilot_id' => $pilot->id,
            'airline_icao' => 'AVA',
            'flight_number' => '001',
            'origin' => 'SEQM',
            'destination' => 'SEGU',
            'departs_at' => '2026-06-01 18:00',
            'arrives_at' => '2026-06-01 19:30',
        ]);
        // Available slots have no pilot and shouldn't appear in a reservations report.
        PilotSlot::factory()->create(['event_id' => $event->id]);

        $user = User::factory()->director()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.events.pilot-slots.report', $event))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $rows = $this->csvRows($response->streamedContent());

        $this->assertSame(['Event', 'Callsign', 'Route', 'VID', 'EOBT', 'ETA'], $rows[0]);
        $this->assertCount(2, $rows);
        $this->assertSame([
            'Fly-In 2026',
            'AVA001',
            'SEQM → SEGU',
            '123456',
            '18:00 UTC',
            '19:30 UTC',
        ], $rows[1]);
    }

    #[Test]
    public function report_excludes_available_slots(): void
    {
        $event = Event::factory()->create(['pilot_slots_enabled' => true]);
        PilotSlot::factory()->count(3)->create(['event_id' => $event->id]);
        $user = User::factory()->director()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.events.pilot-slots.report', $event))
            ->assertOk();

        $rows = $this->csvRows($response->streamedContent());

        // Only the header row remains once every available slot is excluded.
        $this->assertCount(1, $rows);
    }

    #[Test]
    public function report_leaves_eta_blank_when_the_slot_has_no_arrival_time(): void
    {
        $event = Event::factory()->create(['pilot_slots_enabled' => true]);
        $pilot = User::factory()->create();

        PilotSlot::factory()->confirmed()->arrival()->create([
            'event_id' => $event->id,
            'pilot_id' => $pilot->id,
            'arrives_at' => null,
        ]);

        $user = User::factory()->director()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.events.pilot-slots.report', $event))
            ->assertOk();

        $rows = $this->csvRows($response->streamedContent());

        $this->assertSame('', $rows[1][5]);
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function csvRows(string $content): array
    {
        $lines = array_filter(explode("\n", trim($content)));

        return array_map(str_getcsv(...), array_values($lines));
    }
}
