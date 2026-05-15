<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Models\TrainingRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingsIndexTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_trainings(): void
    {
        $this->get(route('dashboard.trainings.index'))
            ->assertRedirect(route('home'));
    }

    #[Test]
    public function authenticated_user_can_view_trainings_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.trainings.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests')
                ->has('availableAtcTrainings')
                ->has('availablePilotTrainings')
            );
    }

    #[Test]
    public function user_can_see_their_own_training_requests(): void
    {
        $user = User::factory()->create();
        $request = TrainingRequest::factory()->create(['trainee_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('dashboard.trainings.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 1, fn ($page) => $page
                    ->where('id', $request->id)
                    ->etc()
                )
            );
    }

    #[Test]
    public function user_does_not_see_other_users_training_requests(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        TrainingRequest::factory()->create(['trainee_id' => $other->id]);

        $this->actingAs($user)
            ->get(route('dashboard.trainings.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 0)
            );
    }

    #[Test]
    public function available_atc_trainings_are_filtered_by_user_rating(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.trainings.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('availableAtcTrainings')
                ->has('availablePilotTrainings')
            );
    }
}
