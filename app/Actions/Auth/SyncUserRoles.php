<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\Role;
use App\Models\User;
use Spatie\Permission\Models\Role as SpatieRole;

class SyncUserRoles
{
    /**
     * Sync the user roles from their IVAO staff positions. Positions issued by
     * another division are ignored, since staff position ids are shared across
     * divisions (e.g. "XG-TC" and "CO-TC" both resolve to Role::TC).
     */
    public function handle(User $user): User
    {
        /** @var array<int, mixed> */
        $staffPositions = $user->raw_data['userStaffPositions'] ?? [];

        $roles = collect($staffPositions)
            ->filter(fn (array $position): bool => ($position['divisionId'] ?? null) === Role::DIVISION)
            ->map(fn (array $position): ?Role => Role::fromStaffPositionId($position['staffPositionId'] ?? ''))
            ->filter()
            ->unique(fn (Role $role) => $role->value)
            ->map(fn (Role $role) => SpatieRole::firstOrCreate(['name' => $role->value]))
            ->all();

        return $user->syncRoles($roles);
    }
}
