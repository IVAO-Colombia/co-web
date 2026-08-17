<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Models\TrainingRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingRequestsShowTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_training_request_show(): void
    {
        $request = TrainingRequest::factory()->create();

        $this->get(route('dashboard.staff.training-requests.show', $request))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function users_without_permission_cannot_view_a_training_request(): void
    {
        $user = User::factory()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.staff.training-requests.show', $request))
            ->assertForbidden();
    }

    #[Test]
    public function staff_can_view_a_training_request(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.show', $request))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('trainingRequest', fn ($page) => $page
                    ->where('id', $request->id)
                    ->has('trainee')
                    ->etc()
                )
                ->has('assignableStaff')
            );
    }

    #[Test]
    public function view_only_staff_can_view_a_training_request(): void
    {
        $viewer = User::factory()->membershipCoordinator()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($viewer)
            ->get(route('dashboard.staff.training-requests.show', $request))
            ->assertOk();
    }

    #[Test]
    public function assignable_staff_only_contains_users_who_can_be_assigned(): void
    {
        $staff = User::factory()->trainingCoordinator()->create();
        $trainer = User::factory()->trainer()->create();
        $director = User::factory()->director()->create();
        $request = TrainingRequest::factory()->create();

        $response = $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.show', $request))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('assignableStaff', 2, fn ($page) => $page
                    ->hasAll(['id', 'name', 'vid'])
                    ->etc()
                )
            );

        $assignableIds = collect($response->viewData('page')['props']['assignableStaff'])
            ->pluck('id')
            ->all();

        $this->assertContains($trainer->id, $assignableIds);
        $this->assertContains($staff->id, $assignableIds);
        $this->assertNotContains($director->id, $assignableIds);
    }
}
