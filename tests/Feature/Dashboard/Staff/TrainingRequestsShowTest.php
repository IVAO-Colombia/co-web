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
        $staff = User::factory()->director()->create();
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
    public function assignable_staff_contains_users_with_manage_permission(): void
    {
        $staff = User::factory()->director()->create();
        User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($staff)
            ->get(route('dashboard.staff.training-requests.show', $request))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('assignableStaff', fn ($page) => $page
                    ->hasAll(['0.id', '0.name', '0.vid'])
                    ->etc()
                )
            );
    }
}
