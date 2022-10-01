<?php

namespace App\Policies;

use App\Models\{User, Team};
use Illuminate\Auth\Access\HandlesAuthorization;

class VirtualairlinePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        $teamewebmaster = Team::find(10);
        $teamoperacionesvuelo = Team::find(7);

        if (
            ($user->currentTeam == $teamewebmaster &&
                $teamewebmaster->hasUser($user)) ||
            ($user->currentTeam == $teamoperacionesvuelo &&
                $teamoperacionesvuelo->hasUser($user))
        ) {
            return true;
        } else {
            return false;
        }
    }
}
