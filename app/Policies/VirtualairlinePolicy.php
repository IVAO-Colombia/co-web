<?php

namespace App\Policies;

use App\Models\{User, Team};
use Illuminate\Auth\Access\HandlesAuthorization;

class VirtualairlinePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        $teamewebmaster = Team::find(10);

        if (
            $user->currentTeam == $teamewebmaster &&
            $teamewebmaster->hasUser($user)
        ) {
            return true;
        } else {
            return false;
        }
    }
}
