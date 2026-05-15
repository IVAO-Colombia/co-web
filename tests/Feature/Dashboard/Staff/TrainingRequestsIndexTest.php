<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

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
            ->assertRedirect(route('home'));
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
    public function staff_can_view_all_training_requests(): void
    {
        $staff = User::factory()->director()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 1, fn ($page) => $page
                    ->where('id', $request->id)
                    ->etc()
                )
                ->has('counts')
            );
    }

    #[Test]
    public function staff_can_filter_by_status(): void
    {
        $staff = User::factory()->director()->create();
        TrainingRequest::factory()->pending()->create();
        TrainingRequest::factory()->scheduled()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index', ['status' => 'pending']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequests.data', 1)
            );
    }

    #[Test]
    public function staff_can_filter_by_type(): void
    {
        $staff = User::factory()->director()->create();
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
    public function counts_reflect_pending_and_scheduled_totals(): void
    {
        $staff = User::factory()->director()->create();
        TrainingRequest::factory()->pending()->count(3)->create();
        TrainingRequest::factory()->scheduled()->count(2)->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('counts.pending', 3)
                ->where('counts.scheduled', 2)
            );
    }
}
