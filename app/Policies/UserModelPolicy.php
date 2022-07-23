<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserModelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any user models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the user model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserModel  $userModel
     * @return mixed
     */
    public function view(User $user, UserModel $userModel)
    {
        return $userModel->user_id === $user->id;
    }

    /**
     * Determine whether the user can create user models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the user model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserModel  $userModel
     * @return mixed
     */
    public function update(User $user, UserModel $userModel)
    {
        //
    }

    /**
     * Determine whether the user can delete the user model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserModel  $userModel
     * @return mixed
     */
    public function delete(User $user, UserModel $userModel)
    {
        //
    }

    /**
     * Determine whether the user can restore the user model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserModel  $userModel
     * @return mixed
     */
    public function restore(User $user, UserModel $userModel)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the user model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserModel  $userModel
     * @return mixed
     */
    public function forceDelete(User $user, UserModel $userModel)
    {
        //
    }
}
