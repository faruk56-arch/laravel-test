<?php

namespace Tests\Browser\Pages;

use App\Models\Category;
use App\Models\Modele;
use App\Models\Part;
use App\Models\User;
use Laravel\Dusk\Browser;

class SearchEngine extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/model';
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
        return [
        ];
    }

    public function researchModele(Browser $browser)
    {
        $modele = Modele::first();
        $browser->type('researchModele', $modele->name)
            ->waitForText($modele->brand->name)
            ->keys('#researchModele', ['{ARROW_DOWN}'])
            ->keys('#researchModele', ['{ENTER}'])
            ->assertSee('Étape suivante');
    }

    public function category(Browser $browser)
    {
        $category = Category::has('parts')->first();
        $browser->clickLink($category->translation)
            ->assertSee('Étape suivante');
    }

    public function researchPart(Browser $browser)
    {
        $part = Part::first();
        $browser->waitFor('#select-car-parts')
            ->type('researchPart', $part->translation)
            ->waitFor('@part-item')
            ->clickLink($part->translation)
            ->assertVisible('@part-next');
    }

    public function register(Browser $browser)
    {
        $user = factory(User::class)->make();

        $browser->type('firstname', $user->firstname)
            ->type('lastname', $user->lastname)
            ->type('zip', '123456')
            ->select('country')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->type('repeat_password', 'password')
            ->press('Terminer')
            ->waitForText(' cliquer ici.');
    }
}
