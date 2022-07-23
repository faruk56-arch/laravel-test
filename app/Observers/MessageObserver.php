<?php

namespace App\Observers;

use App\Models\Message;
use App\Notifications\Notif;
use Notification;

class MessageObserver
{
    /**
     * Handle the message "created" event.
     *
     * @param \App\Message $message
     * @return void
     */
    public function created(Message $message)
    {
        //
    }

    /**
     * Handle the message "updated" event.
     *
     * @param \App\Message $message
     * @return void
     */
    public function updated(Message $message)
    {
        if ($message->wasChanged('checked')) {
            if ($message->checked == 1) {
                $message->load('template', 'sender', 'recipient');
                Notification::send(
                    $message->recipient()->first(),
                    new Notif(
                        $message,
                        'created',
                        808,
                        ['url' => env('APP_URL').'/fr/finder/inbox']
                    )
                );
            } elseif ($message->checked == 2) {
                $message->load('template', 'sender', 'recipient');
                Notification::send(
                    $message->sender()->first(),
                    new Notif(
                        $message,
                        'created',
                        807,
                        ['url' => env('APP_URL').'/fr/finder/inbox']
                    )
                );
            }
        }
    }

    /**
     * Handle the message "deleted" event.
     *
     * @param \App\Message $message
     * @return void
     */
    public function deleted(Message $message)
    {
        //
    }

    /**
     * Handle the message "restored" event.
     *
     * @param \App\Message $message
     * @return void
     */
    public function restored(Message $message)
    {
        //
    }

    /**
     * Handle the message "force deleted" event.
     *
     * @param \App\Message $message
     * @return void
     */
    public function forceDeleted(Message $message)
    {
        //
    }
}
