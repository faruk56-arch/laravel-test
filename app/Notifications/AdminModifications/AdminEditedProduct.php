<?php

namespace App\Notifications\AdminModifications;

use App\Models\Product;
use App\Models\User;
use App\Services\MergeModels\SendInBlueData;
use App\Services\SendInBlue;
use App\Services\SendInBlueInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminEditedProduct extends Notification implements SendInBlueInterface
{
    use Queueable;

    /**
     * @var \App\Models\Product
     */
    private $product;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SendInBlue::class];
    }

    public function toSendInBlue(User $notifiable): SendInBlueData
    {
//        TODO: TemplateId
        return SendInBlueData::factory(999, [
            'product' => $this->product->toArray(),
            'modifications' => $this->product->modifications()->toArray(),
        ]);
    }
}
