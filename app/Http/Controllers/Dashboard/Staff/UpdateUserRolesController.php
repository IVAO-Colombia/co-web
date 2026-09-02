<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Staff;

use App\Actions\Auth\SyncUserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRolesRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UpdateUserRolesController extends Controller
{
    /**
     * Replace the roles of a member with the ones picked by a webmaster.
     *
     * Roles are recomputed from the member's IVAO staff positions on their next
     * login, so these manual changes are not permanent.
     *
     * @see SyncUserRoles
     */
    public function __invoke(UpdateUserRolesRequest $request, User $user): RedirectResponse
    {
        $user->syncRoles($request->roles());

        return back()->with('success', __('Roles updated.'));
    }
}
