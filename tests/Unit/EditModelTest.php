<?php

namespace Tests\Unit;

use App\Actions\AdminModifications\EditModelAction;
use App\Models\Research;
use App\Notifications\AdminModifications\AdminEditedProduct;
use App\Notifications\AdminModifications\AdminEditedResearch;
use Notification;
use Tests\TestCase;

class EditModelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testNotify(): void
    {
        $research = Research::first();
        Notification::fake();
        $user = $research->user;
        $obj = new EditModelAction($research);
        $obj->notify();
        Notification::assertSentTo($user, AdminEditedResearch::class);
        Notification::assertNotSentTo($user, AdminEditedProduct::class);
    }

    public function testApply(): void
    {
        // creation
        $changes = collect(['name' => 'test', 'details' => 'test_details']);
        $research = Research::first();
        $obj = new EditModelAction($research);
        $obj->apply($changes);
        $modifications = collect($research->getMeta('modifications'));
        self::assertTrue($modifications->contains('test'));
        // updates
        $changes = collect(['name' => 'test_changed']);
        $obj->apply($changes);
        $modifications = collect($research->getMeta('modifications'));
        self::assertTrue($modifications->contains('test_changed'));
        self::assertFalse($modifications->contains('test_details'));
    }

    public function testFilterFields(): void
    {
        $research = Research::first();
        $research->name = 'test_1';
        $research->details = 'test_1';
        $research->img = ['test_1'];
        $obj = new EditModelAction($research);
        $changes = collect(['name' => 'test', 'details' => 'test_details', 'img' => ['test', 'test_1']]);
        $res = $obj->filterUnchangedFields($changes);
        self::assertArrayHasKey('name', $res);
        self::assertTrue($changes->diffAssoc($res)->isEmpty());
        $changes = collect(['img' => ['test', 'test_1']]);
        $res = $obj->filterUnchangedFields($changes);
        self::assertArrayHasKey('img', $res);
        self::assertTrue($changes->diffAssoc($res)->isEmpty());
        $changes = collect(['name' => 'test', 'details' => 'test_details']);
        $res = $obj->filterUnchangedFields($changes);
        self::assertArrayHasKey('name', $res);
        self::assertTrue($changes->diffAssoc($res)->isEmpty());
    }

    public function testCompareImgField(): void
    {
        $research = Research::first();
        $research->img = ['test_1', 'test_2'];
        $obj = new EditModelAction($research);
        $res = $obj->compareImgField($newValues = collect(['img' => ['test_1', 'test_2']]));
        self::assertArrayNotHasKey('img', $newValues);
        self::assertNull($res);
        $res = $obj->compareImgField($newValues = collect(['img' => ['test_1', 'test_3']]));
        self::assertEquals(collect(['img' => ['test_1', 'test_3']]), $res);

    }

    public function testExecute(): void
    {
        Notification::fake();
        $research = Research::first();
        $research->name = 'test_1';
        $research->details = 'test_1';
        $research->img = ['test_1'];
        $changes = collect();
        $modifications = $research->getMeta('modifications');
        EditModelAction::execute($research, $changes);
        self::assertSame($research->getMeta('modifications'), $modifications);
        $changes = collect(['name' => 'test', 'details' => 'test_details', 'img' => ['test', 'test_1']]);
        EditModelAction::execute($research, $changes);
        self::assertSame($research->getMeta('modifications')->name, 'test');
    }
}
