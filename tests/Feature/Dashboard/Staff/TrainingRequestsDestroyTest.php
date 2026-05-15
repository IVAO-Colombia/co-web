<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Enums\TrainingRequestStatus;
use App\Models\TrainingRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingRequestsDestroyTest extends TestCase
{
    #[Test]
    public function guests_cannot_cancel_a_training_request(): void
    {
        $request = TrainingRequest::factory()->create();

        $this->delete(route('dashboard.staff.training-requests.destroy', $request))
            ->assertRedirect(route('home'));
    }

    #[Test]
    public function users_without_permission_cannot_cancel_a_training_request(): void
    {
        $user = User::factory()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($user)
            ->delete(route('dashboard.staff.training-requests.destroy', $request))
            ->assertForbidden();
    }

    #[Test]
    public function staff_can_cancel_any_pending_request(): void
    {
        $staff = User::factory()->director()->create();
        $request = TrainingRequest::factory()->pending()->create();

        $this->actingAs($staff)
            ->delete(route('dashboard.staff.training-requests.destroy', $request))
            ->assertRedirect(route('dashboard.staff.training-requests.index'));

        $request->refresh();
        $this->assertEquals(TrainingRequestStatus::Cancelled, $request->status);
    }

    #[Test]
    public function staff_can_cancel_a_scheduled_request(): void
    {
        $staff = User::factory()->director()->create();
        $request = TrainingRequest::factory()->scheduled()->create();

        $this->actingAs($staff)
            ->delete(route('dashboard.staff.training-requests.destroy', $request))
            ->assertRedirect(route('dashboard.staff.training-requests.index'));

        $request->refresh();
        $this->assertEquals(TrainingRequestStatus::Cancelled, $request->status);
    }

    #[Test]
    public function trainer_can_cancel_a_request(): void
    {
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->pending()->create();

        $this->actingAs($trainer)
            ->delete(route('dashboard.staff.training-requests.destroy', $request))
            ->assertRedirect(route('dashboard.staff.training-requests.index'));

        $request->refresh();
        $this->assertEquals(TrainingRequestStatus::Cancelled, $request->status);
    }
}
