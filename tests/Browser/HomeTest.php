<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Home;
use Tests\DuskTestCase;

class HomeTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testResearch()
    {
        $this->seed('BrandModeleCategoryPartSeeder');
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home)
                ->research()
                ->assertPathIsNot('/');
        });
    }
}
