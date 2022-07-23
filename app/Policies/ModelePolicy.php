<?php

namespace App\Policies;

use App\Models\Modele;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModelePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any modeles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the modele.
     *
     * @param  \App\Models\User  $user
     * @param  Modele  $modele
     * @return mixed
     */
    public function view(User $user, Modele $modele)
    {
        return true;
    }

    /**
     * Determine whether the user can create modeles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the modele.
     *
     * @param  \App\Models\User  $user
     * @param  Modele  $modele
     * @return mixed
     */
    public function update(User $user, Modele $modele)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the modele.
     *
     * @param  \App\Models\User  $user
     * @param  Modele  $modele
     * @return mixed
     */
    public function delete(User $user, Modele $modele)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the modele.
     *
     * @param  \App\Models\User  $user
     * @param  Modele  $modele
     * @return mixed
     */
    public function restore(User $user, Modele $modele)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the modele.
     *
     * @param  \App\Models\User  $user
     * @param  Modele  $modele
     * @return mixed
     */
    public function forceDelete(User $user, Modele $modele)
    {
        return false;
    }
}
