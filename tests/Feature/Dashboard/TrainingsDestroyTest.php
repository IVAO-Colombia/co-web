<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Enums\TrainingRequestStatus;
use App\Models\TrainingRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingsDestroyTest extends TestCase
{
    #[Test]
    public function guests_cannot_cancel_a_training_request(): void
    {
        $request = TrainingRequest::factory()->pending()->create();

        $this->delete(route('dashboard.trainings.destroy', $request))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function user_can_cancel_their_own_pending_request(): void
    {
        $user = User::factory()->create();
        $request = TrainingRequest::factory()->pending()->create(['trainee_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.trainings.destroy', $request))
            ->assertRedirect(route('dashboard.trainings.index'));

        $request->refresh();
        $this->assertEquals(TrainingRequestStatus::Cancelled, $request->status);
    }

    #[Test]
    public function user_cannot_cancel_another_users_request(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $request = TrainingRequest::factory()->pending()->create(['trainee_id' => $other->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.trainings.destroy', $request))
            ->assertForbidden();
    }

    #[Test]
    public function user_cannot_cancel_a_non_pending_request(): void
    {
        $user = User::factory()->create();
        $request = TrainingRequest::factory()->scheduled()->create(['trainee_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.trainings.destroy', $request))
            ->assertSessionHasErrors('status');
    }

    #[Test]
    public function user_cannot_cancel_an_already_cancelled_request(): void
    {
        $user = User::factory()->create();
        $request = TrainingRequest::factory()->cancelled()->create(['trainee_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('dashboard.trainings.destroy', $request))
            ->assertSessionHasErrors('status');
    }
}
