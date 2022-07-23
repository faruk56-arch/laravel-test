<?php

namespace Tests\Browser;

use App\Models\Country;
use App\Models\Research;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\SearchEngine;
use Tests\DuskTestCase;

class SearchEngineTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testWizardRegister()
    {
        factory(Country::class)->create();
        $this->seed('BrandModeleCategoryPartSeeder');
        $this->browse(
            function (Browser $browser) {
                $browser->visit(new SearchEngine)
                    ->storeConsoleLog('hi.log')
                    ->researchModele()
                    ->click('@modele-next')
                    ->category()
                    ->click('@category-next')
                    ->researchPart()
                    ->click('@part-next')
                    ->click('@details-next')
                    ->register()
                    ->click('@click-here');
                $research = Research::first();
                $browser->assertSee($research->modele->modele->name);
            }
        );
    }
}
