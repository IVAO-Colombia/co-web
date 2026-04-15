<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permission as EnumsPermission;
use App\Enums\Role as EnumsRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * This class uses rolesPermissionsAssignmentMap() to create roles and assign permissions to them.
 * If a permission isn't found in the assignment map, it will be deleted if it exists in the db.
 *
 * This seeder needs to be run after a deployment if the roles or permissions have changed.
 */
class SpatieRolesAndPermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createRolesAndAssignPermissions()
            ->createNonRolePermissions();

        $deleted = Permission::query()
            ->whereDoesntHave('roles')
            ->whereDoesntHave('users')
            ->delete();

        $this->command->info("Deleted {$deleted} orphaned permissions.");

        // reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function createRolesAndAssignPermissions(): self
    {
        $map = $this->rolesPermissionsAssignmentMap();

        foreach ($map as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            assert($role instanceof Role);
            if ($role->wasRecentlyCreated) {
                $this->command->info("Creating role: {$roleName}");
            }

            $permissionsToGive = [];

            foreach ($permissions as $permissionEnum) {
                $permission = $this->getOrCreatePermission($permissionEnum);
                $permissionsToGive[] = $permission->id;
            }

            $role->syncPermissions($permissionsToGive);
        }

        return $this;
    }

    private function createNonRolePermissions(): self
    {
        foreach (EnumsPermission::directlyAssignable() as $permission) {
            $this->getOrCreatePermission($permission);
        }

        return $this;
    }

    private function getOrCreatePermission(EnumsPermission $permissionEnum): Permission
    {
        $permission = Permission::firstOrCreate(['name' => $permissionEnum->value]);

        assert($permission instanceof Permission);
        if ($permission->wasRecentlyCreated) {
            $this->command->info("Creating permission: {$permissionEnum->value}");
        }

        return $permission;
    }

    /**
     * Array map with roles and the permissions associated with each role.
     * If a permission is not found in the map, it will be deleted.
     *
     * @return array<string, EnumsPermission[]>
     */
    public function rolesPermissionsAssignmentMap(): array
    {
        return [
            EnumsRole::DIR => [
                EnumsPermission::eventsPermissions(),
            ],
            EnumsRole::ADIR => [
                EnumsPermission::eventsPermissions(),
            ],
            EnumsRole::FOC => [],
            EnumsRole::FOAC => [],
            EnumsRole::AOC => [],
            EnumsRole::AOAC => [],
            EnumsRole::TC => [],
            EnumsRole::TAC => [],
            EnumsRole::TA => [],
            EnumsRole::T0 => [],
            EnumsRole::MC => [],
            EnumsRole::EC => [
                EnumsPermission::eventsPermissions(),
            ],
            EnumsRole::EAC => [
                EnumsPermission::eventsPermissions(),
            ],
            EnumsRole::EA => [
                EnumsPermission::eventsPermissions(),
            ],
            EnumsRole::PRC => [],
            EnumsRole::PRAC => [],
            EnumsRole::PRA => [],
            EnumsRole::WM => [
                EnumsPermission::eventsPermissions(),
            ],
            EnumsRole::AWM => [
                EnumsPermission::eventsPermissions(),
            ],
            EnumsRole::WMA => [
                EnumsPermission::eventsPermissions(),
            ],
        ];
    }
}
