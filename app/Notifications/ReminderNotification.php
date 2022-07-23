<?php

namespace App\Notifications;

use App\Channels\SIB;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notification;

class ReminderNotification extends Notification
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
        User $user,
        $params = []
    ) {
        $this->template = $template;
        $this->user = $user;
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

    public function toSendInBlue($notifiable)
    {
        $params = array_merge(
            ['products' => $this->models->toArray(), 'url'=> env('APP_URL').'/fr/model'],
            $this->params
        );
        if ($this->user) {
            return [
                'template' => $this->template, 'params' => $params,
                'to'       => $this->user->email,
                'name'     => $this->user->firstname.' '
                    .$this->user->lastname,
            ];
        }
    }
}
