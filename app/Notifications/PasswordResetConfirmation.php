<?php

namespace App\Notifications;

use App\Channels\SIB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PasswordResetConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    private $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [SIB::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toSendInBlue($notifiable)
    {
        return [
            'template' => 635,
            'to'       => $notifiable->email,
            'name'     => $notifiable->firstname.' '.$notifiable->lastname,
            'params'   => [
                'url'       => env('APP_URL').'/fr/login',
                'firstname' => $notifiable->firstname,
                'lastname'  => $notifiable->lastname,
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
