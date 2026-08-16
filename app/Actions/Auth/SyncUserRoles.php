<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\Role;
use App\Models\User;
use Spatie\Permission\Models\Role as SpatieRole;

class SyncUserRoles
{
    public function handle(User $user): User
    {
        /** @var array<int, mixed> */
        $staffPositions = $user->raw_data['userStaffPositions'] ?? [];

        $roles = collect($staffPositions)
            ->map(fn (array $position): ?\App\Enums\Role => Role::fromStaffPositionId($position['staffPositionId']))
            ->filter()
            ->unique(fn (Role $role) => $role->value)
            ->map(fn (Role $role) => SpatieRole::firstOrCreate(['name' => $role->value]))
            ->all();

        return $user->syncRoles($roles);
    }
}
