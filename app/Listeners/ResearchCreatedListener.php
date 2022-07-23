<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\Notif as SendNotif;
use Illuminate\Support\Facades\Notification;

class ResearchCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Notification::send(
            User::find($event->research->user_id),
            new SendNotif('Recherche '.$event->research->id.' créée!')
        );
    }
}
