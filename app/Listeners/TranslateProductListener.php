<?php

namespace App\Listeners;

use App\Actions\Translations\DeepLTranslation;
use App\Events\ProductValidated;

class TranslateProductListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductValidated  $event
     * @return void
     */
    public function handle(ProductValidated $event)
    {
        DeepLTranslation::execute($event->product, 'name');
        DeepLTranslation::execute($event->product, 'description');
    }
}
