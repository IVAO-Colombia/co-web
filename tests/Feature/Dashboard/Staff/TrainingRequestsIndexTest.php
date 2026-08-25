<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Enums\TrainingRequestStatus;
use App\Models\TrainingRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingRequestsIndexTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_staff_training_requests(): void
    {
        $this->get(route('dashboard.staff.training-requests.index'))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function users_without_permission_cannot_access_staff_training_requests(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertForbidden();
    }

    #[Test]
    public function view_only_staff_can_list_training_requests(): void
    {
        $viewer = User::factory()->membershipCoordinator()->create();
        TrainingRequest::factory()->create();

        $this->actingAs($viewer)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertOk();
    }

    #[Test]
    public function staff_can_view_all_training_requests(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 1, fn ($page) => $page
                    ->where('id', $request->id)
                    ->etc()
                )
                ->has('assignableTrainers')
                ->has('unassignedPendingCount')
            );
    }

    #[Test]
    public function cancelled_and_completed_requests_are_hidden_by_default(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        TrainingRequest::factory()->pending()->create();
        TrainingRequest::factory()->scheduled()->create();
        TrainingRequest::factory()->cancelled()->create();
        TrainingRequest::factory()->create(['status' => TrainingRequestStatus::COMPLETED]);

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 2)
            );
    }

    #[Test]
    public function staff_can_filter_by_explicit_statuses(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        TrainingRequest::factory()->pending()->create();
        TrainingRequest::factory()->cancelled()->create();
        TrainingRequest::factory()->create(['status' => TrainingRequestStatus::COMPLETED]);

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index', [
                'statuses' => ['cancelled', 'completed'],
            ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 2)
            );
    }

    #[Test]
    public function an_invalid_status_is_rejected_instead_of_erroring(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index', [
                'statuses' => ['not-a-real-status'],
            ]))
            ->assertSessionHasErrors('statuses.0');
    }

    #[Test]
    public function staff_can_filter_by_type(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        TrainingRequest::factory()->create();
        TrainingRequest::factory()->pilot()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index', ['type' => 'pilot']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 1)
            );
    }

    #[Test]
    public function staff_can_filter_by_a_specific_trainer(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        TrainingRequest::factory()->create(['trainer_id' => $trainer->id]);
        TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index', ['trainer_id' => $trainer->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 1)
            );
    }

    #[Test]
    public function staff_can_filter_by_unassigned_requests(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        TrainingRequest::factory()->create(['trainer_id' => $trainer->id]);
        TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index', ['trainer_id' => 'unassigned']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 1)
            );
    }

    #[Test]
    public function unassigned_pending_requests_are_listed_before_assigned_pending_requests_and_scheduled_requests(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();

        $scheduled = TrainingRequest::factory()->scheduled()->create();
        $assignedPending = TrainingRequest::factory()->pending()->create(['trainer_id' => $trainer->id]);
        $unassignedPending = TrainingRequest::factory()->pending()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index', [
                'statuses' => ['pending', 'scheduled'],
            ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('trainingRequests.data.0.id', $unassignedPending->id)
                ->where('trainingRequests.data.1.id', $assignedPending->id)
                ->where('trainingRequests.data.2.id', $scheduled->id)
            );
    }

    #[Test]
    public function assignable_trainers_carry_their_active_workload_by_type(): void
    {
        // A view-only role, so it never shows up in the assignable list itself.
        $staff = User::factory()->membershipCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $nonAssignable = User::factory()->membershipCoordinator()->create();

        TrainingRequest::factory()->pending()->create(['trainer_id' => $trainer->id]);
        TrainingRequest::factory()->scheduled()->create(['trainer_id' => $trainer->id]);
        TrainingRequest::factory()->pilot()->pending()->create(['trainer_id' => $trainer->id]);
        // Not active: should not count towards the workload.
        TrainingRequest::factory()->cancelled()->create(['trainer_id' => $trainer->id]);
        TrainingRequest::factory()->create(['trainer_id' => $nonAssignable->id]);

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('assignableTrainers', 1, fn ($page) => $page
                    ->where('id', $trainer->id)
                    ->where('atc_trainings_count', 2)
                    ->where('pilot_trainings_count', 1)
                    ->etc()
                )
            );
    }

    #[Test]
    public function unassigned_pending_count_only_counts_pending_requests_without_a_trainer(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();

        TrainingRequest::factory()->pending()->count(2)->create();
        TrainingRequest::factory()->pending()->create(['trainer_id' => $trainer->id]);
        TrainingRequest::factory()->scheduled()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('unassignedPendingCount', 2)
            );
    }
}
