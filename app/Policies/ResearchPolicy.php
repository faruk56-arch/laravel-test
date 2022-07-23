<?php

namespace App\Policies;

use App\Models\Research;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResearchPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any researches.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the research.
     *
     * @param  \App\Models\User  $user
     * @param  Research  $research
     * @return mixed
     */
    public function view(User $user, Research $research)
    {
        return $research->user_id === $user->id;
    }

//
//    /**
//     * Determine whether the user can create researches.
//     *
//     * @param  \App\Models\User  $user
//     * @return mixed
//     */
//    public function create(User $user)
//    {
//        return true;
//    }

    /**
     * Determine whether the user can update the research.
     *
     * @param  \App\Models\User  $user
     * @param  Research  $research
     * @return mixed
     */
    public function update(User $user, Research $research)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the research.
     *
     * @param  \App\Models\User  $user
     * @param  Research  $research
     * @return mixed
     */
    public function delete(User $user, Research $research)
    {
        return $research->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the research.
     *
     * @param  \App\Models\User  $user
     * @param  Research  $research
     * @return mixed
     */
    public function restore(User $user, Research $research)
    {
        return $research->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the research.
     *
     * @param  \App\Models\User  $user
     * @param  Research  $research
     * @return mixed
     */
    public function forceDelete(User $user, Research $research)
    {
        return false;
    }
}
