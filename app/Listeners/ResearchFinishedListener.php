<?php

namespace App\Listeners;

use App\Events\ResearchFinishedEvent as ResearchFinishedEvent;
use App\Models\User;
use App\Notifications\Notif as SendNotif;
use Illuminate\Support\Facades\Notification;

class ResearchFinishedListener
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
    public function handle(ResearchFinishedEvent $event)
    {
        Notification::send(
            User::find($event->research->user_id),
            new SendNotif('Recherche '.$event->research->id.' terminée!')
        );
    }
}
