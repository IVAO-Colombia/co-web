<?php

namespace App\Policies;

use App\Models\Documentation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentationPolicy
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
        $teamwebmaster = Team::find(10);
        $teamentrenamiento = Team::find(4);

        if(($user->currentTeam == $teamentrenamiento &&
        $teamentrenamiento->hasUser($user))||
            ($user->currentTeam == $teamwebmaster &&
            $teamwebmaster->hasUser($user))
        ){
            return true;
        } else {
            return false;
        }
    }
}
