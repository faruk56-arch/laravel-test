<?php

namespace App\Policies;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MerchantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any merchants.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the merchant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Merchant  $merchant
     *
     * @return mixed
     */
    public function view(User $user, Merchant $merchant)
    {
        //
    }

    /**
     * Determine whether the user can create merchants.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the merchant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Merchant  $merchant
     *
     * @return mixed
     */
    public function update(User $user, Merchant $merchant)
    {
        return $user->merchant->id === $merchant->id
            && $merchant->type === 'Pro';
    }

    /**
     * Determine whether the user can delete the merchant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Merchant  $merchant
     *
     * @return mixed
     */
    public function delete(User $user, Merchant $merchant)
    {
        //
    }

    /**
     * Determine whether the user can restore the merchant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Merchant  $merchant
     *
     * @return mixed
     */
    public function restore(User $user, Merchant $merchant)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the merchant.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Merchant  $merchant
     *
     * @return mixed
     */
    public function forceDelete(User $user, Merchant $merchant)
    {
        //
    }
}
