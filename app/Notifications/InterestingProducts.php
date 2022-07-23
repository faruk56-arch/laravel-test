<?php

namespace App\Notifications;

use App\Channels\SIB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class InterestingProducts extends Notification
{
    use Queueable;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $modeles;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Collection $modeles)
    {
        $this->modeles = $modeles;
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
            'template' => 951,
            'to'       => $notifiable->email,
            'name'     => $notifiable->firstname.' '.$notifiable->lastname,
            'params'   => [
                'modeles' => $this->modeles,
                'url' => config('app.url').'/fr'.'/product/',
                'firstname' => $notifiable->firstname,
                'lastname'  => $notifiable->lastname,
            ]
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
