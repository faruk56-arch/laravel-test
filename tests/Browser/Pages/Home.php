<?php

namespace Tests\Browser\Pages;

use App\Models\Modele;
use App\Models\Part;
use Laravel\Dusk\Browser;

class Home extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
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
        $browser->assertSee('Comment ça marche ?');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@research' => 'input[name=research]',
        ];
    }

    public function research(Browser $browser)
    {
        $modele = Modele::first();
        $part = Part::first();
        $browser->type('researchModele', $modele->name)
            ->waitForText($modele->brand->name)
            ->keys('#researchModele', ['{ARROW_DOWN}'])
            ->keys('#researchModele', ['{ENTER}'])
            ->type('researchPart', $part->translation)
            ->waitForText($part->translation)
            ->keys('#researchPart', ['{ARROW_DOWN}'])
            ->keys('#researchPart', ['{ENTER}'])
            ->click('#app > div > div > div > section.head >
            div.container > div > div.col-12.col-md-10.offset-md-1.research
            > div > div.col-12.col-md-3.pl-md-0 > span');
    }
}
