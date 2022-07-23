<?php

namespace App\Notifications;

use App\Channels\SIB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use URL;

class EmailValidation extends Notification implements ShouldQueue
{
    use Queueable;

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
            'template' => 802,
            'to'       => $notifiable->getMeta('new_email'),
            'name'     => $notifiable->firstname.' '.$notifiable->lastname,
            'params'   => [
                'first_name' => $notifiable->firstname,
                'last_name'  => $notifiable->lastname,
                'url'        => $this->webUrl($notifiable),
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

    /**
     * @param $email
     *
     * @return string
     */
    public function emailValidationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'email-validation',
            now()->addDays(3),
            [
                'user'  => $notifiable->id,
                'email' => $notifiable->getMeta('new_email'),
            ]
        );
    }

    public function webUrl($notifiable)
    {
        return env('APP_URL').'/fr/email-validation?q='
            .base64_encode($this->emailValidationUrl($notifiable));
    }
}
