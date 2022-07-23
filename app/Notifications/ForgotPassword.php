<?php

namespace App\Notifications;

use App\Channels\SIB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ForgotPassword extends Notification implements ShouldQueue
{
    use Queueable;

    private $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $name = $notifiable->firstname.' '.$notifiable->lastname === ' '
            ? $notifiable->email
            : $notifiable->firstname.' '.$notifiable->lastname;

        return [
            'template' => 634,
            'to'       => $notifiable->email,
            'name'     => $name,
            'params'   => [
                'url'       => env('APP_URL').'/fr/password-change/'.$this->token,
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
