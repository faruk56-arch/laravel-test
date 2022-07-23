<?php

namespace Tests\Unit;


use App\Actions\AdminModifications\ValidateModelAction;
use App\Models\Product;
use App\Models\Research;
use App\Models\User;
use App\Notifications\AdminModifications\UserRefusedResearchEdit;
use Illuminate\Http\UploadedFile;
use Notification;
use Storage;
use Tests\TestCase;

class ValidateModelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRemoveUImages(): void
    {
        Storage::fake('public');
        $location = Storage::disk('public')->put("researches", UploadedFile::fake()->image('test.jpg'));
        $location_keep = Storage::disk('public')->put("researches", UploadedFile::fake()->image('test_keep.jpg'));
        $research = Research::latest()->first();
        $research->img = ['/storage/'.$location, '/storage/'.$location_keep];
        $research->setMeta('modifications', ['img' => ['/storage/'.$location_keep, 'test_3']]);
        $obj = new ValidateModelAction($research);
        $obj->removeUnusedImages($research->modifications()['img'], $research->img);
        Storage::disk('public')->assertMissing($location);
        Storage::disk('public')->assertExists($location_keep);
    }

    public function testAccept()
    {
        Storage::fake('public');
        $oldLocation = Storage::disk('public')->put("researches", UploadedFile::fake()->image('old_test.jpg'));
        $location = Storage::disk('public')->put("researches", UploadedFile::fake()->image('test.jpg'));
        $location_keep = Storage::disk('public')->put("researches", UploadedFile::fake()->image('test_keep.jpg'));
        $research = Research::latest()->first();
        $research->img = ['/storage/'.$oldLocation, '/storage/'.$location_keep];
        $research->setMeta('modifications', ['img' => ['/storage/'.$location, '/storage/'.$location_keep]]);
        ValidateModelAction::accept($research);
        Storage::disk('public')->assertMissing($oldLocation);
        Storage::disk('public')->assertExists($location_keep);
        Storage::disk('public')->assertExists($location);
        self::assertEquals(['/storage/'.$location, '/storage/'.$location_keep], $research->img);
    }

    public function testAcceptProduct()
    {
        Storage::fake('public');
        $oldLocation = Storage::disk('public')->put("researches", UploadedFile::fake()->image('old_test.jpg'));
        $location = Storage::disk('public')->put("researches", UploadedFile::fake()->image('test.jpg'));
        $location_keep = Storage::disk('public')->put("researches", UploadedFile::fake()->image('test_keep.jpg'));
        $product = Product::latest()->first();
        $product->img = ['/storage/'.$oldLocation, '/storage/'.$location_keep];
        $product->setMeta('modifications', ['description' => 'test', 'img' => ['/storage/'.$location, '/storage/'.$location_keep]]);
        self::assertNotNull($product->getMeta('modifications'));
        ValidateModelAction::accept($product);
        Storage::disk('public')->assertMissing($oldLocation);
        Storage::disk('public')->assertExists($location_keep);
        Storage::disk('public')->assertExists($location);
        self::assertEquals(['/storage/'.$location, '/storage/'.$location_keep], $product->img);
        self::assertEquals('test', $product->description);
    }


    public function testRefuse()
    {
        Storage::fake('public');
        Notification::fake();
        $oldLocation = Storage::disk('public')->put("researches", UploadedFile::fake()->image('old_test.jpg'));
        $location = Storage::disk('public')->put("researches", UploadedFile::fake()->image('test.jpg'));
        $location_keep = Storage::disk('public')->put("researches", UploadedFile::fake()->image('test_keep.jpg'));
        $research = Research::latest()->first();
        $research->img = ['/storage/'.$oldLocation, '/storage/'.$location_keep];
        $research->setMeta('modifications', ['img' => ['/storage/'.$location, '/storage/'.$location_keep]]);
        ValidateModelAction::refuse($research);
        Storage::disk('public')->assertExists($oldLocation);
        Storage::disk('public')->assertExists($location_keep);
        Storage::disk('public')->assertMissing($location);
        self::assertEquals(['/storage/'.$oldLocation, '/storage/'.$location_keep], $research->img);
        self::assertNull($research->getMeta('modifications'));
        Notification::assertSentTo(User::where('role', 'admin')->get(), UserRefusedResearchEdit::class);
    }
}
