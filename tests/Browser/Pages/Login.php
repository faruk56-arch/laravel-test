<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Login extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
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
        $browser->assertSee('Connexion');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@submit' => 'button[type=submit]',
        ];
    }

    /**
     * @param  \Laravel\Dusk\Browser  $browser
     * @param  string  $email
     * @param  string  $password
     */
    public function loginUser(
        Browser $browser,
        $email = 'test@test.fr',
        $password = 'password'
    ) {
        $browser->type('email', $email)
            ->type('password', $password)
            ->press('@submit');
    }
}
