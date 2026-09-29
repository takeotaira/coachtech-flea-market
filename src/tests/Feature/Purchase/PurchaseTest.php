<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_purchase(): void
    {
        [$buyer, $item] = $this->createTestData();

        $sessionsMock = Mockery::mock();
        $sessionsMock
            ->shouldReceive('retrieve')
            ->once()
            ->with('test-session')
            ->andReturn((object) [
                'status' => 'complete',
                'metadata' => (object) [
                    'user_id' => (string) $buyer->id,
                    'item_id' => (string) $item->id,
                    'payment_method' => 'カード支払い',
                    'shipping_postal_code' => '123-4567',
                    'shipping_address' => '東京都テスト区1-2-3',
                    'shipping_building' => 'テストビル101',
                ],
            ]);

        $stripeMock = Mockery::mock(
            'overload:' . \Stripe\StripeClient::class
        );

        $stripeMock
            ->shouldReceive('__construct')
            ->once()
            ->with(Mockery::any())
            ->andSet('checkout', (object) [
                'sessions' => $sessionsMock,
            ]);

        $response = $this
            ->actingAs($buyer)
            ->get(route('purchases.success', [
                'itemId' => $item->id,
                'session_id' => 'test-session',
            ]));

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都テスト区1-2-3',
            'shipping_building' => 'テストビル101',
        ]);
    }

    public function test_purchased_item_is_displayed_as_sold(): void
    {
        [$buyer, $item] = $this->createTestData();

        $this->createPurchase($buyer, $item);

        $response = $this
            ->actingAs($buyer)
            ->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertSee('購入対象商品');
        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_displayed_in_profile(): void
    {
        [$buyer, $item] = $this->createTestData();

        $this->createPurchase($buyer, $item);

        $response = $this
            ->actingAs($buyer)
            ->get(route('mypage', [
                'page' => 'buy',
            ]));

        $response->assertStatus(200);
        $response->assertSee('購入対象商品');
    }

    private function createTestData(): array
    {
        $buyer = User::forceCreate([
            'name' => '購入者',
            'email' => 'buyer@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => 'password',
        ]);

        $conditionId = DB::table('conditions')->insertGetId([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => '購入対象商品',
            'description' => '購入対象商品の説明',
            'price' => 5000,
            'image_path' => 'images/item.jpg',
        ]);

        return [$buyer, $item];
    }

    private function createPurchase(
        User $buyer,
        Item $item
    ): Purchase {
        return Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都テスト区1-2-3',
            'shipping_building' => 'テストビル101',
        ]);
    }
}