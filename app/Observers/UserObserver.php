<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\EmailValidation;
use App\Notifications\OldEmailValidation;
use App\Notifications\PasswordChanged;
use App\Notifications\SetPassword;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     *
     * @return void
     */
    public function created(User $user)
    {
        if ($user->password === null) {
            $token = Str::uuid();
            $user->notify(new SetPassword($token));
            $user->update(['password_reset_token' => $token]);
        }
    }

    //end created()

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     */
    public function updated(User $user)
    {
        $originalPassword = $user->getOriginal('password');
        if ($user->isDirty('password') && $originalPassword !== null) {
            $user->notify(new PasswordChanged);
        }
    }

    //end updated()

    public function updating(User $user)
    {
        if ($user->isDirty('email')) {
            $user->setMeta('new_email', $user->email);
            $user->notify(new EmailValidation);
            $user->notify(new OldEmailValidation($user->getOriginal('email')));

            return false;
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     *
     * @return void
     */
    public function deleted(User $user)
    {
        if (auth()->user()->id === $user->id) {
            $user->email .= '-deleted'.$user->id;

            $user->saveQuietly();
        }

        $researches = $user->researches;
        if ($researches) {
            foreach ($researches as $research) {
                $research->products()->detach();
            }
            $user->researches()->update(['status' => 'archived']);
        }
        $merchant = $user->merchant;
        if ($merchant) {
            if ($merchant->alerts) {
                $merchant->alerts()->update(['active' => 0]);
            }
            if ($merchant->products) {
                $merchant->products()->update(['status' => 3]);
            }
        }
    }

    //end deleted()

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     *
     * @return void
     */
    public function restored(User $user)
    {
    }

    //end restored()

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     *
     * @return void
     */
    public function forceDeleted(User $user)
    {
    }

    //end forceDeleted()
}//end class
