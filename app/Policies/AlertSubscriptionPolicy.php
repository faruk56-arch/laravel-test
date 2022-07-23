<?php

namespace App\Policies;

use App\AlertSubscription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlertSubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any alert subscriptions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the alert subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\AlertSubscription  $alertSubscription
     * @return mixed
     */
    public function view(User $user, AlertSubscription $alertSubscription)
    {
        //
    }

    /**
     * Determine whether the user can create alert subscriptions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the alert subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\AlertSubscription  $alertSubscription
     * @return mixed
     */
    public function update(User $user, AlertSubscription $alertSubscription)
    {
        //
    }

    /**
     * Determine whether the user can delete the alert subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\AlertSubscription  $alertSubscription
     * @return mixed
     */
    public function delete(User $user, AlertSubscription $alertSubscription)
    {
        //
    }

    /**
     * Determine whether the user can restore the alert subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\AlertSubscription  $alertSubscription
     * @return mixed
     */
    public function restore(User $user, AlertSubscription $alertSubscription)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the alert subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\AlertSubscription  $alertSubscription
     * @return mixed
     */
    public function forceDelete(User $user, AlertSubscription $alertSubscription)
    {
        //
    }
}
