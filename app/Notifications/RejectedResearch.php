<?php

namespace App\Notifications;

use App\Channels\SIB;
use App\Models\Research;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RejectedResearch extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $message;

    private $research;

    /**
     * Create a new notification instance.
     *
     * @param  string  $message
     * @param  \App\Models\Research  $research
     */
    public function __construct(String $message, Research $research)
    {
        $this->message = $message;
        $this->research = $research;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [SIB::class];
    }

    public function toSendInBlue($notifiable): array
    {
        return [
            'template' => 797,
            'to'       => $notifiable->email,
            'name'     => $notifiable->firstname.' '.$notifiable->lastname,
            'params'   => [
                'firstname' => $notifiable->firstname,
                'lastname'  => $notifiable->lastname,
                'research' => $this->research->toArray(),
                'message' => $this->message,
                'url' => env('APP_URL').'/fr/model',
            ],
        ];
    }
}
