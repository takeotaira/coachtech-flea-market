<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_changed_address_is_displayed_on_purchase_page(): void
    {
        [$buyer, $item] = $this->createTestData();

        $response = $this
            ->actingAs($buyer)
            ->post(route('purchases.address.update', [
                'itemId' => $item->id,
            ]), [
                'postal_code' => '987-6543',
                'address' => '神奈川県横浜市1-2-3',
                'building' => 'テストマンション101',
            ]);

        $response->assertRedirect(route('purchases.create', [
            'itemId' => $item->id,
        ]));

        $response->assertSessionHas(
            'purchase_address.' . $item->id,
            [
                'postal_code' => '987-6543',
                'address' => '神奈川県横浜市1-2-3',
                'building' => 'テストマンション101',
            ]
        );

        $response = $this
            ->actingAs($buyer)
            ->get(route('purchases.create', [
                'itemId' => $item->id,
            ]));

        $response->assertStatus(200);
        $response->assertSee('987-6543');
        $response->assertSee('神奈川県横浜市1-2-3');
        $response->assertSee('テストマンション101');
    }

    public function test_purchase_is_saved_with_changed_shipping_address(): void
    {
        [$buyer, $item] = $this->createTestData();

        $this
            ->actingAs($buyer)
            ->post(route('purchases.address.update', [
                'itemId' => $item->id,
            ]), [
                'postal_code' => '987-6543',
                'address' => '神奈川県横浜市1-2-3',
                'building' => 'テストマンション101',
            ]);

        $sessionsMock = Mockery::mock();

        $sessionsMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function (array $data): bool {
                return $data['metadata']['shipping_postal_code']
                        === '987-6543'
                    && $data['metadata']['shipping_address']
                        === '神奈川県横浜市1-2-3'
                    && $data['metadata']['shipping_building']
                        === 'テストマンション101';
            }))
            ->andReturn((object) [
                'url' => 'https://checkout.stripe.test/session',
            ]);

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
                    'shipping_postal_code' => '987-6543',
                    'shipping_address' => '神奈川県横浜市1-2-3',
                    'shipping_building' => 'テストマンション101',
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
            ->post(route('purchases.store', [
                'itemId' => $item->id,
            ]), [
                'payment_method' => 'カード支払い',
            ]);

        $response->assertRedirect(
            'https://checkout.stripe.test/session'
        );

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
            'shipping_postal_code' => '987-6543',
            'shipping_address' => '神奈川県横浜市1-2-3',
            'shipping_building' => 'テストマンション101',
        ]);
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
}