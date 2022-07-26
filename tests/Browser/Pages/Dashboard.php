<?php

namespace Tests\Browser\Pages;

use App\Models\Research;
use Laravel\Dusk\Browser;

class Dashboard extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/finder/dashboard';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    public function latestResearches(Browser $browser)
    {
        $research = Research::first();
        $browser->waitUntilMissing('@research-loading')
            ->assertSee($research->modele->car_name);
    }
}
