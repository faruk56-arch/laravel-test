<?php

namespace App\Notifications;

use App\Channels\SIB;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class Notif extends Notification
{
    protected $template = null;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    private $state;

    /**
     * @var null
     */
    private $params;

    /**
     * Create a new notification instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param $state
     * @param  null  $template
     * @param  null  $params
     */
    public function __construct(
        Model $model,
        $state,
        $template = null,
        $params = []
    ) {
        $this->template = $template;
        $this->model = $model;
        $this->state = $state;
        $this->params = $params;
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
        if ($this->model->type_id) {
            return [SIB::class];
        } elseif ($this->template !== null) {
            return ['database', SIB::class];
        }

        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return array
     */
    public function toSendInBlue($notifiable)
    {
        $model = $this->model->toArray();
        $model['part']['translation'] = array_key_exists('part', $model) ?
            translateObject('name', $model['part']) : 'N/A';
        $params = array_merge(
            ['object' => $model],
            $this->params
        );
        if ($this->model->user) {
            return [
                'template' => $this->template, 'params' => $params,
                'to'       => $this->model->user->email,
                'name'     => $this->model->user->firstname.' '
                    .$this->model->user->lastname,
            ];
        } elseif ($this->model->type_id && $this->template !== 726 && $this->template !== 807) {
            return [
                'template' => $this->template, 'params' => $params,
                'to'       => $this->model->recipient()->first()->email,
                'name'     => $this->model->recipient()->first()->firstname.' '
                    .$this->model->recipient()->first()->lastname,
            ];
        } elseif ($this->model->type_id) {
            return [
                'template' => $this->template, 'params' => $params,
                'to'       => $this->model->sender()->first()->email,
                'name'     => $this->model->sender()->first()->firstname.' '
                    .$this->model->sender()->first()->lastname,
            ];
        }

        return [
            'template' => $this->template, 'params' => $params,
            'to'       => $this->model->merchant->user[0]->email,
            'name'     => $this->model->merchant->user[0]->firstname.' '
                .$this->model->merchant->user[0]->lastname,
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
        $status = Status::firstWhere('name', '=', $this->state);

        return [
            $this->model->getTable() => $this->model->toArray(),
            'state'                  => $status->toArray(),
        ];
    }
}
