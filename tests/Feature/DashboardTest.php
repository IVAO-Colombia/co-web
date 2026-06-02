<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ATCRating;
use App\Enums\PilotRating;
use App\Enums\TrainingRequestStatus;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\TrainingRequest;
use App\Models\User;
use App\Services\Ivao\Ivao;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_to_the_home_page(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function authenticated_users_can_visit_the_dashboard(): void
    {
        $this->mock(Ivao::class)->shouldReceive('getTrackerSessions')->andReturn([]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    #[Test]
    public function dashboard_passes_parsed_hours_from_raw_data(): void
    {
        $this->mock(Ivao::class)->shouldReceive('getTrackerSessions')->andReturn([]);

        $user = User::factory()->create([
            'raw_data' => [
                'hours' => [
                    ['type' => 'pilot', 'hours' => 3600],
                    ['type' => 'atc', 'hours' => 7200],
                    ['type' => 'staff', 'hours' => 1800],
                ],
            ],
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('hours.pilot', 3600)
                ->where('hours.atc', 7200)
                ->where('hours.staff', 1800)
            );
    }

    #[Test]
    public function dashboard_handles_missing_hours_in_raw_data(): void
    {
        $this->mock(Ivao::class)->shouldReceive('getTrackerSessions')->andReturn([]);

        $user = User::factory()->create(['raw_data' => null]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('hours.pilot', 0)
                ->where('hours.atc', 0)
                ->where('hours.staff', 0)
            );
    }

    #[Test]
    public function dashboard_passes_active_training_request_count(): void
    {
        $this->mock(Ivao::class)->shouldReceive('getTrackerSessions')->andReturn([]);

        $user = User::factory()->create();
        TrainingRequest::factory()->create(['trainee_id' => $user->id, 'status' => TrainingRequestStatus::Pending]);
        TrainingRequest::factory()->create(['trainee_id' => $user->id, 'status' => TrainingRequestStatus::Scheduled]);
        TrainingRequest::factory()->create(['trainee_id' => $user->id, 'status' => TrainingRequestStatus::Completed]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('activeTrainingRequestsCount', 2)
            );
    }

    #[Test]
    public function dashboard_passes_reservation_counts(): void
    {
        $this->mock(Ivao::class)->shouldReceive('getTrackerSessions')->andReturn([]);

        $user = User::factory()->create();
        $event = Event::factory()->create();
        AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $user->id]);
        AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $user->id]);
        PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('reservationsCount.atc', 2)
                ->where('reservationsCount.pilot', 1)
            );
    }

    #[Test]
    public function dashboard_passes_atc_and_pilot_ratings(): void
    {
        $this->mock(Ivao::class)->shouldReceive('getTrackerSessions')->andReturn([]);

        $user = User::factory()->create([
            'atc_rating' => ATCRating::AS3,
            'pilot_rating' => PilotRating::FS3,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('atcRating.key', 'AS3')
                ->where('atcRating.label', ATCRating::AS3->label())
                ->where('pilotRating.key', 'FS3')
                ->where('pilotRating.label', PilotRating::FS3->label())
            );
    }

    #[Test]
    public function dashboard_passes_tracker_sessions(): void
    {
        $sessions = [
            ['id' => 1, 'callsign' => 'CO-AWM', 'connectionType' => 'pilot', 'time' => 3600, 'createdAt' => '2026-06-01T12:00:00.000Z', 'completedAt' => '2026-06-01T13:00:00.000Z'],
        ];

        $this->mock(Ivao::class)->shouldReceive('getTrackerSessions')->andReturn($sessions);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trackerSessions', 1)
                ->where('trackerSessions.0.callsign', 'CO-AWM')
            );
    }

    #[Test]
    public function dashboard_shows_empty_tracker_sessions_when_api_fails(): void
    {
        $this->mock(Ivao::class)->shouldReceive('getTrackerSessions')->andThrow(new \Exception('API error'));

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('trackerSessions', [])
            );
    }
}
