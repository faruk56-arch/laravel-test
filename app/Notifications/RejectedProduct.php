<?php

namespace App\Notifications;

use App\Channels\SIB;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RejectedProduct extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $message;

    private $product;

    /**
     * Create a new notification instance.
     *
     * @param  string  $message
     * @param  Product  $product
     */
    public function __construct(String $message, Product $product)
    {
        $this->message = $message;
        $this->product = $product;
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
            'template' => 813,
            'to'       => $notifiable->email,
            'name'     => $notifiable->firstname.' '.$notifiable->lastname,
            'params'   => [
                'firstname' => $notifiable->firstname,
                'lastname'  => $notifiable->lastname,
                'product' => $this->product->toArray(),
                'message' => $this->message,
                'url' => env('APP_URL').'/fr/finder/catalog/addProduct',
            ],
        ];
    }
}
