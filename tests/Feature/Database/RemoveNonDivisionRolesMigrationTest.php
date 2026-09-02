<?php

declare(strict_types=1);

namespace Tests\Feature\Database;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RemoveNonDivisionRolesMigrationTest extends TestCase
{
    #[Test]
    public function it_removes_every_role_of_users_from_another_division(): void
    {
        $user = User::factory()->trainingCoordinator()->create(['division_id' => 'XG']);

        $this->runMigration();

        $this->assertEmpty($user->fresh()->roles);
    }

    #[Test]
    public function it_removes_every_role_of_users_without_a_division(): void
    {
        $user = User::factory()->director()->create(['division_id' => null]);

        $this->runMigration();

        $this->assertEmpty($user->fresh()->roles);
    }

    #[Test]
    public function it_keeps_the_roles_of_the_users_of_the_division(): void
    {
        $user = User::factory()->trainingCoordinator()->create(['division_id' => Role::DIVISION]);

        $this->runMigration();

        $this->assertTrue($user->fresh()->hasRole(Role::TC->value));
    }

    private function runMigration(): void
    {
        /** @var Migration $migration */
        $migration = require database_path('migrations/2026_09_02_140451_remove_roles_from_users_of_other_divisions.php');

        $migration->up();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
