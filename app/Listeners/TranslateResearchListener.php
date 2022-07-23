<?php

namespace App\Listeners;

use App\Actions\Translations\DeepLTranslation;
use App\Events\ResearchValidated;

class TranslateResearchListener
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
     * @param  ResearchValidated  $event
     * @return void
     */
    public function handle(ResearchValidated $event): void
    {
        $research = $event->research;
        DeepLTranslation::execute($research, 'details');
    }
}
