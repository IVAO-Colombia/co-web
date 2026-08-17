<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Enums\TrainingRequestStatus;
use App\Models\TrainingRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingRequestsAssignTrainerTest extends TestCase
{
    #[Test]
    public function guests_cannot_assign_a_trainer(): void
    {
        $request = TrainingRequest::factory()->create();

        $this->patch(route('dashboard.staff.training-requests.trainer.update', $request), [])
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function training_coordinators_can_assign_a_trainer(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $trainer->id,
            ])
            ->assertRedirect(route('dashboard.staff.training-requests.show', $request));

        $request->refresh();
        $this->assertEquals($trainer->id, $request->trainer_id);
    }

    #[Test]
    public function training_assistant_coordinators_can_assign_a_trainer(): void
    {
        $coordinator = User::factory()->trainingAssistantCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $trainer->id,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertEquals($trainer->id, $request->trainer_id);
    }

    #[Test]
    public function directors_can_assign_a_trainer(): void
    {
        $director = User::factory()->director()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($director)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $trainer->id,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertEquals($trainer->id, $request->trainer_id);
    }

    #[Test]
    public function trainers_cannot_assign_a_trainer(): void
    {
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($trainer)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $trainer->id,
            ])
            ->assertForbidden();
    }

    #[Test]
    public function training_advisors_cannot_assign_a_trainer(): void
    {
        $advisor = User::factory()->trainingAdvisor()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($advisor)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $advisor->id,
            ])
            ->assertForbidden();
    }

    #[Test]
    public function the_trainer_cannot_be_changed_on_a_cancelled_request(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->cancelled()->create();

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $trainer->id,
            ])
            ->assertForbidden();

        $request->refresh();
        $this->assertNull($request->trainer_id);
    }

    #[Test]
    public function the_trainer_cannot_be_changed_on_a_completed_request(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create([
            'status' => TrainingRequestStatus::COMPLETED,
        ]);

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $trainer->id,
            ])
            ->assertForbidden();

        $request->refresh();
        $this->assertNull($request->trainer_id);
    }

    #[Test]
    public function a_user_who_cannot_be_assigned_is_rejected(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $director = User::factory()->director()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $director->id,
            ])
            ->assertSessionHasErrors('trainer_id');

        $request->refresh();
        $this->assertNull($request->trainer_id);
    }

    #[Test]
    public function a_trainer_can_be_unassigned(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create(['trainer_id' => $trainer->id]);

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => null,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertNull($request->trainer_id);
    }

    #[Test]
    public function every_assignment_change_is_appended_to_the_history(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => $trainer->id,
            ]);

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.training-requests.trainer.update', $request), [
                'trainer_id' => null,
            ]);

        $request->refresh();
        $history = $request->assignment_history;

        $this->assertCount(2, $history);

        $this->assertEquals($coordinator->id, $history[0]['by_id']);
        $this->assertEquals($coordinator->name, $history[0]['by_name']);
        $this->assertEquals($trainer->id, $history[0]['trainer_id']);
        $this->assertEquals($trainer->name, $history[0]['trainer_name']);
        $this->assertNotEmpty($history[0]['at']);

        $this->assertNull($history[1]['trainer_id']);
        $this->assertNull($history[1]['trainer_name']);
    }
}
