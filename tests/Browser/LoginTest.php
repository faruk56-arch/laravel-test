<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @throws \Throwable
     */
    public function testLogin()
    {
        factory(User::class)->create([
            'email'    => 'test@test.fr',
            'password' => 'password',
        ]);

        $this->browse(
            function (Browser $browser) {
                $browser->visit(new Login)
                    ->loginUser()
                    ->waitUntilMissingText('E-mail')
                    ->assertPathIsNot('/login');
            }
        );
    }

    //    public function testWrongCredentials()
    //    {
    //        $this->browse(function (Browser $browser) {
    //            $browser->visit('/login')
    //                ->type('#email', 'test@test.fr')
    //                ->type('#password', 'wrongpassword')
    //                ->press('Connexion')
    //                ->waitForText('E-mail ou mot de passe incorrect')
    //                ->assertPathIsNot('/login');
    //        });
    //    }
}
