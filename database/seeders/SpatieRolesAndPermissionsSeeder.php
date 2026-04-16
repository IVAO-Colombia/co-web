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
            EnumsRole::DIR->value => [EnumsPermission::STAFF_ACCESS]+EnumsPermission::eventsPermissions(),
            EnumsRole::ADIR->value => [EnumsPermission::STAFF_ACCESS]+EnumsPermission::eventsPermissions(),
            EnumsRole::FOC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::FOAC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::AOC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::AOAC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::TC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::TAC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::TA->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::T0->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::MC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::EC->value => [EnumsPermission::STAFF_ACCESS]+EnumsPermission::eventsPermissions(),
            EnumsRole::EAC->value => [EnumsPermission::STAFF_ACCESS]+EnumsPermission::eventsPermissions(),
            EnumsRole::EA->value => [EnumsPermission::STAFF_ACCESS]+EnumsPermission::eventsPermissions(),
            EnumsRole::PRC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::PRAC->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::PRA->value => [EnumsPermission::STAFF_ACCESS],
            EnumsRole::WM->value => [EnumsPermission::STAFF_ACCESS]+EnumsPermission::eventsPermissions(),
            EnumsRole::AWM->value => [EnumsPermission::STAFF_ACCESS]+EnumsPermission::eventsPermissions(),
            EnumsRole::WMA->value => [EnumsPermission::STAFF_ACCESS]+EnumsPermission::eventsPermissions(),
        ];
    }
}
