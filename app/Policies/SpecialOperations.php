<?php

namespace App\Policies;

use App\Models\Departments;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecialOperations
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
        $teamEpecialOps = Team::find(6);
        $teamWebmaster = Team::find(10);
        $teamHq = Team::find(9);

        if (
            ($user->currentTeam == $teamEpecialOps &&
                $teamEpecialOps->hasUser($user)) ||
            ($user->currentTeam == $teamWebmaster &&
                $teamWebmaster->hasUser($user)) ||
            ($user->currentTeam == $teamHq && $teamHq->hasUser($user))
        ) {
            return true;
        } else {
            return false;
        }
    }

    // /**
    //  * Determine whether the user can view the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Departments  $departments
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function view(User $user, Departments $departments)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can create models.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function create(User $user)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can update the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Departments  $departments
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function update(User $user, Departments $departments)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can delete the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Departments  $departments
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function delete(User $user, Departments $departments)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can restore the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Departments  $departments
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function restore(User $user, Departments $departments)
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @param  \App\Models\Departments  $departments
    //  * @return \Illuminate\Auth\Access\Response|bool
    //  */
    // public function forceDelete(User $user, Departments $departments)
    // {
    //     //
    // }
}
