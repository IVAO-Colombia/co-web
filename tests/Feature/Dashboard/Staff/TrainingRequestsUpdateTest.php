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
    public function staff_can_assign_a_trainer(): void
    {
        $staff = User::factory()->director()->create();
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'trainer_id' => $trainer->id,
            ])
            ->assertRedirect(route('dashboard.staff.training-requests.show', $request));

        $request->refresh();
        $this->assertEquals($trainer->id, $request->trainer_id);
    }

    #[Test]
    public function staff_can_schedule_a_session_date(): void
    {
        $staff = User::factory()->director()->create();
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
        $staff = User::factory()->director()->create();
        $request = TrainingRequest::factory()->pending()->create();

        $this->actingAs($staff)
            ->patch(route('dashboard.staff.training-requests.update', $request), [
                'status' => TrainingRequestStatus::Scheduled->value,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertEquals(TrainingRequestStatus::Scheduled, $request->status);
    }

    #[Test]
    public function staff_can_add_public_and_internal_observations(): void
    {
        $staff = User::factory()->director()->create();
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
}
