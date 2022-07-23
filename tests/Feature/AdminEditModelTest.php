<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Research;
use App\Models\User;
use App\Notifications\AdminModifications\AdminEditedProduct;
use App\Notifications\AdminModifications\AdminEditedResearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JWTAuth;
use Notification;
use Tests\TestCase;

class AdminEditModelTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testEditProduct(): void
    {
        Notification::fake();
        $admin = User::where('role', 'admin')->first();
        $token = JWTAuth::fromUser($admin);
        $product = Product::latest()->first();
        $product->description = 'test_unmodified';
        $product->saveWithoutEvents();
        $response = $this->post('/api/admin-modifications/admin-edit/product/'.$product->id, [
            'description' => 'test_modification'
        ],
            ['Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
        self::assertEquals('test_modification', $product->refresh()->modifications()['description']);
        Notification::assertSentTo($product->merchant->user, AdminEditedProduct::class);
    }

    public function testEditResearch(): void
    {
        Notification::fake();
        $admin = User::where('role', 'admin')->first();
        $token = JWTAuth::fromUser($admin);
        $research = Research::latest()->first();
        $research->details = 'test_unmodified';
        $research->saveWithoutEvents();
        $response = $this->post('/api/admin-modifications/admin-edit/research/'.$research->id, [
            'details' => 'test_modification'
        ],
            ['Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
        self::assertEquals('test_modification', $research->refresh()->modifications()['details']);
        Notification::assertSentTo($research->user, AdminEditedResearch::class);
    }
}
