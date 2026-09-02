<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Roles used to be assigned from every IVAO staff position, regardless of
     * the division that issued it, granting staff access to members of other
     * divisions. Detach every role from those members; the ones still holding
     * a staff position of this division get their roles back on their next
     * login, now that the sync ignores foreign staff positions.
     */
    public function up(): void
    {
        DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->whereIn('model_id', DB::table('users')
                ->select('id')
                ->where(fn (Builder $query) => $query
                    ->where('division_id', '!=', Role::DIVISION)
                    ->orWhereNull('division_id')
                )
            )
            ->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible: the removed role assignments are not recorded anywhere.
    }
};
