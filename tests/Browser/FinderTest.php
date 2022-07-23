<?php

namespace Tests\Browser;

use App\Models\Research;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class FinderTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testDashboard()
    {
        factory(User::class)->create(['email'    => 'test@test.fr',
                                      'password' => 'password',
        ]);
        $this->seed('BrandModeleCategoryPartSeeder');
        factory(UserModel::class)->create();
        factory(Research::class)->create();
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->loginUser()
                ->visit(new Dashboard)
                ->latestResearches();
        });
    }
}
