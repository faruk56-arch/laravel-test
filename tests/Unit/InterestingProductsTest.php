<?php

namespace Tests\Unit;

use App\Actions\AdminModifications\EditModelAction;
use App\Models\Research;
use App\Notifications\AdminModifications\AdminEditedProduct;
use App\Notifications\AdminModifications\AdminEditedResearch;
use App\Notifications\InterestingProducts;
use Carbon\Carbon;
use Notification;
use Tests\TestCase;

class InterestingProductsTest extends TestCase
{

    public function test_it_notifies()
    {
        Notification::fake();
        $time = Carbon::create(2021, 12, 1, 0, 0, 0);
        Carbon::setTestNow($time);
        $o = new \App\Actions\InterestingProductsAction();
        $users = $o->query();
        self::assertNotEmpty($users);
        $o->execute();
        Notification::assertSentTo($users[0], InterestingProducts::class);
    }
}
