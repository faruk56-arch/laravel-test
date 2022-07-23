<?php

namespace App\Notifications;

use App\Channels\SIB;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class ResearchArchived extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        Model $model,
        $template = null,
        $params = []
    ) {
        $this->template = $template;
        $this->model = $model;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toSendInBlue($notifiable)
    {
        $params = array_merge(
            ['object' => $this->model->toArray()],
            $this->params
        );
        if ($this->model->user) {
            return [
                'template' => $this->template, 'params' => $params,
                'to'       => $this->model->user->email,
                'name'     => $this->model->user->firstname.' '
                    .$this->model->user->lastname,
            ];
        }
    }
}
