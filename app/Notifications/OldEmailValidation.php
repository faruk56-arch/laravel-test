<?php

namespace App\Notifications;

use App\Channels\SIB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OldEmailValidation extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    private $email;

    /**
     * Create a new notification instance.
     *
     * @param  string  $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    //end __construct()

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

    //end via()

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toSendInBlue($notifiable)
    {
        return [
            'template' => 803,
            'to'       => $this->email,
            'name'     => $notifiable->firstname.' '.$notifiable->lastname,
            'params'   => [
                'first_name' => $notifiable->firstname,
                'last_name'  => $notifiable->lastname,
                'new_email'  => $notifiable->getMeta('new_email'),
            ],
        ];
    }

    //end toSendInBlue()

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }

    //end toArray()
}//end class
