<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Enums\TrainingRequestStatus;
use App\Models\TrainingRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingRequestsUpdateTest extends TestCase
{
    #[Test]
    public function guests_cannot_update_a_training_request(): void
    {
        $request = TrainingRequest::factory()->create();

        $this->patch(route('dashboard.staff.training-requests.update', $request), [])
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function users_without_permission_cannot_update_a_training_request(): void
    {
        $user = User::factory()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($user)
            ->patch(route('dashboard.staff.training-requests.update', $request), [])
            ->assertForbidden();
    }

    #[Test]
    public function view_only_staff_cannot_update_a_training_request(): void
    {
        $viewer = User::factory()->membershipCoordinator()->create();
        $request = TrainingRequest::factory()->pending()->create();

        $this->actingAs($viewer)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'status' => TrainingRequestStatus::SCHEDULED->value,
            ])
            ->assertForbidden();

        $request->refresh();
        $this->assertEquals(TrainingRequestStatus::PENDING, $request->status);
    }

    #[Test]
    public function the_update_endpoint_cannot_change_the_trainer(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'trainer_id' => $trainer->id,
            ])
            ->assertRedirect(route('dashboard.staff.training-requests.show', $request));

        $request->refresh();
        $this->assertNull($request->trainer_id);
    }

    #[Test]
    public function staff_can_schedule_a_session_date(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create();
        $date = now()->addDays(5)->format('Y-m-d H:i');

        $this->actingAs($staff)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'occurs_at' => $date,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertNotNull($request->occurs_at);
    }

    #[Test]
    public function staff_can_update_status(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->pending()->create();

        $this->actingAs($staff)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'status' => TrainingRequestStatus::SCHEDULED->value,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertEquals(TrainingRequestStatus::SCHEDULED, $request->status);
    }

    #[Test]
    public function staff_can_add_public_and_internal_observations(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'public_observations' => 'Session will be via Discord.',
                'internal_observations' => 'Trainee needs extra attention on phraseology.',
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertEquals('Session will be via Discord.', $request->public_observations);
        $this->assertEquals('Trainee needs extra attention on phraseology.', $request->internal_observations);
    }

    #[Test]
    public function the_schedule_is_frozen_once_the_request_is_final(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $originalDate = now()->addDays(2)->startOfMinute();
        $request = TrainingRequest::factory()->create([
            'status' => TrainingRequestStatus::COMPLETED,
            'occurs_at' => $originalDate,
        ]);

        $this->actingAs($staff)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'occurs_at' => now()->addDays(20)->format('Y-m-d H:i'),
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertEquals(
            $originalDate->format('Y-m-d H:i'),
            $request->occurs_at?->format('Y-m-d H:i')
        );
    }

    #[Test]
    public function the_status_can_still_be_changed_once_the_request_is_final(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->cancelled()->create();

        $this->actingAs($staff)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'status' => TrainingRequestStatus::PENDING->value,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertEquals(TrainingRequestStatus::PENDING, $request->status);
    }

    #[Test]
    public function trainers_cannot_overwrite_observations_but_can_still_schedule(): void
    {
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->pending()->create([
            'public_observations' => 'Original public note.',
            'internal_observations' => 'Original internal note.',
        ]);

        $this->actingAs($trainer)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'public_observations' => 'Overwritten.',
                'internal_observations' => 'Overwritten.',
                'status' => TrainingRequestStatus::SCHEDULED->value,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertEquals('Original public note.', $request->public_observations);
        $this->assertEquals('Original internal note.', $request->internal_observations);
        $this->assertEquals(TrainingRequestStatus::SCHEDULED, $request->status);
    }
}
