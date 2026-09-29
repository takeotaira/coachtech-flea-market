<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_liked_items_are_displayed(): void
    {
        $user = $this->createUser(
            'ログインユーザー',
            'user@example.com'
        );

        $seller = $this->createUser(
            '出品者',
            'seller@example.com'
        );

        $conditionId = $this->createCondition();

        $likedItem = $this->createItem(
            $seller,
            $conditionId,
            'いいねした商品'
        );

        $this->createItem(
            $seller,
            $conditionId,
            'いいねしていない商品'
        );

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_sold_label_is_displayed_for_purchased_item(): void
    {
        $user = $this->createUser(
            '購入者',
            'buyer@example.com'
        );

        $seller = $this->createUser(
            '出品者',
            'seller@example.com'
        );

        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            '購入済み商品'
        );

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都テスト区1-2-3',
            'shipping_building' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('購入済み商品');
        $response->assertSee('Sold');
    }

    public function test_no_items_are_displayed_for_guest(): void
    {
        $seller = $this->createUser(
            '出品者',
            'seller@example.com'
        );

        $conditionId = $this->createCondition();

        $this->createItem(
            $seller,
            $conditionId,
            'ゲストには表示されない商品'
        );

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertViewHas(
            'items',
            fn ($items) => $items->isEmpty()
        );
        $response->assertDontSee('ゲストには表示されない商品');
    }

    private function createUser(string $name, string $email): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
        ]);
    }

    private function createCondition(): int
    {
        return DB::table('conditions')->insertGetId([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createItem(
        User $seller,
        int $conditionId,
        string $name
    ): Item {
        return Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => $name,
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'image_path' => 'images/test-item.jpg',
        ]);
    }
}