<?php

namespace App\Notifications;

use App\Channels\SIB;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SuggestedPartAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    private $suggestion;

    private $product;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Product  $product
     * @param $suggestion
     */
    public function __construct(Product $product, $suggestion)
    {
        $this->suggestion = $suggestion;
        $this->product = $product;
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
            'template' => 686,
            'to'       => $notifiable->email,
            'name'     => $notifiable->firstname.' '.$notifiable->lastname,
            'params'   => [
                'url'        => env('APP_URL').'/fr/finder/catalog/addProduct/'
                    .$this->product->id,
                'firstname'  => $notifiable->firstname,
                'lastname'   => $notifiable->lastname,
                'suggestion' => $this->suggestion,
                'object'     => $this->product->toArray(),
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
