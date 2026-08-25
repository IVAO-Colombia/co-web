<?php

declare(strict_types=1);

namespace Tests\Feature\Database;

use App\Enums\Permission;
use App\Enums\TrainingRequestStatus;
use App\Models\TrainingRequest;
use App\Models\User;
use Database\Seeders\LocalTrainingSeeder;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocalTrainingSeederTest extends TestCase
{
    #[Test]
    public function it_seeds_trainers_members_and_training_requests(): void
    {
        $this->seed(LocalTrainingSeeder::class);

        $this->assertSame(6, User::assignableToTrainings()->count());
        $this->assertSame(14, User::query()->whereBetween('vid', [510001, 510014])->count());

        foreach (TrainingRequestStatus::cases() as $status) {
            $this->assertTrue(
                TrainingRequest::query()->where('status', $status)->exists(),
                "Expected at least one {$status->value} training request.",
            );
        }

        $this->assertTrue(TrainingRequest::pending()->whereNull('trainer_id')->exists());
        $this->assertTrue(TrainingRequest::query()->whereNotNull('trainer_id')->exists());
        $this->assertTrue(TrainingRequest::query()->whereNotNull('assignment_history')->exists());
    }

    #[Test]
    public function the_seeded_staff_can_reach_the_training_requests_dashboard(): void
    {
        $this->seed(LocalTrainingSeeder::class);

        $director = User::query()->where('vid', 500001)->sole();
        $membershipCoordinator = User::query()->where('vid', 500007)->sole();

        $this->assertTrue($director->can(Permission::ASSIGN_TRAINING_REQUESTS->value));
        $this->assertTrue($membershipCoordinator->can(Permission::VIEW_TRAINING_REQUESTS->value));
        $this->assertFalse($membershipCoordinator->can(Permission::ASSIGN_TRAINING_REQUESTS->value));

        $this->actingAs($director)
            ->get(route('dashboard.staff.training-requests.index'))
            ->assertOk();
    }
}
