<?php

namespace App\Notifications;

use App\Channels\SIB;
use App\Models\Research;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notification;

class ResearchCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        Collection $models,
        $template = null,
        Research $research,
        $params = []
    ) {
        $this->template = $template;
        $this->research = $research;
        $this->models = $models;
        $this->params = $params;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SIB::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toSendInBlue($notifiable)
    {
        $params = array_merge(
            ['products' => $this->models->toArray()],
            $this->params
        );
        if ($this->research->user) {
            return [
                'template' => $this->template, 'params' => $params,
                'to'       => $this->research->user->email,
                'name'     => $this->research->user->firstname.' '
                    .$this->research->user->lastname,
            ];
        }
    }
}
