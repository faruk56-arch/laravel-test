<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Research;
use App\Models\User;
use App\Notifications\AdminModifications\UserRefusedProductEdit;
use App\Notifications\AdminModifications\UserRefusedResearchEdit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JWTAuth;
use Notification;
use Tests\TestCase;

class ValidateAdminChangesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testProductNotAuthorized(): void
    {
        $product = Product::first();
        $product->setMeta('modifications', ['description' => 'test']);
        $user_wrong = User::latest()->first();
        $token_wrong = JWTAuth::fromUser($user_wrong);
        $res = $this->post('/api/admin-modifications/validate/product/'.$product->id, [
            "accept" => true
        ], ['Authorization' => "Bearer " . $token_wrong]);
        $res->assertStatus(403);
    }

    public function testProductAuthorized(): void
    {
        $product = Product::first();
        $product->description = 'test_wrong';
        $product->save();
        $product->setMeta('modifications', ['description' => 'test']);
        $user = $product->merchant->user->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->post('/api/admin-modifications/validate/product/'.$product->id, [
            "accept" => true
        ], ['Authorization' => "Bearer " . $token]);
        $response->assertStatus(200);
        self::assertEquals('test', $product->refresh()->description);
    }

    public function testResearchAuthorized(): void
    {
        $research = Research::latest()->whereHas('user')->first();
        $research->setMeta('modifications', ['details' => 'test']);
        $research->details = 'test_wrong';
        $research->save();
        $user = $research->user;
        $token = JWTAuth::fromUser($user);

        $response = $this->post('/api/admin-modifications/validate/research/'.$research->id, [
            "accept" => true
        ], ['Authorization' => "Bearer " . $token]);
        $response->assertStatus(200);
        self::assertEquals('test', $research->refresh()->details);

    }


    public function testResearchNotAuthorized(): void
    {
        $research = Research::latest()->whereHas('user')->first();
        $research->setMeta('modifications', ['details' => 'test']);
        $user_wrong = User::latest()->first();
        $token = JWTAuth::fromUser($user_wrong);

        $response = $this->post('/api/admin-modifications/validate/research/'.$research->id, [
            "accept" => true
        ], ['Authorization' => "Bearer " . $token]);
        $response->assertStatus(403);
    }

    public function testResearchRefused()
    {
        Notification::fake();
        $research = Research::latest()->whereHas('user')->first();
        $research->setMeta('modifications', ['details' => 'test']);
        $user = $research->user;
        $token = JWTAuth::fromUser($user);

        $response = $this->post('/api/admin-modifications/validate/research/'.$research->id, [
            "accept" => false
        ], ['Authorization' => "Bearer " . $token]);
        $response->assertStatus(200);
        Notification::assertSentTo(User::where('role', 'admin')->get(), UserRefusedResearchEdit::class);
    }

    public function testProductRefused()
    {
        Notification::fake();
        $product = Product::first();
        $product->setMeta('modifications', ['description' => 'test']);
        $user = $product->merchant->user->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->post('/api/admin-modifications/validate/product/'.$product->id, [
            "accept" => false
        ], ['Authorization' => "Bearer " . $token]);
        $response->assertStatus(200);
        Notification::assertSentTo(User::where('role', 'admin')->get(), UserRefusedProductEdit::class);
    }
}
