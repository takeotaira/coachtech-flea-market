<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed(): void
    {
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

        Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => 'テスト商品A',
            'description' => '商品の説明A',
            'price' => 1000,
            'image_path' => 'images/test-a.jpg',
        ]);

        Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => 'テスト商品B',
            'description' => '商品の説明B',
            'price' => 2000,
            'image_path' => 'images/test-b.jpg',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('テスト商品A');
        $response->assertSee('テスト商品B');
    }

    public function test_sold_label_is_displayed_for_purchased_item(): void
    {
        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => 'password',
        ]);

        $buyer = User::create([
            'name' => '購入者',
            'email' => 'buyer@example.com',
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
            'name' => '購入済み商品',
            'description' => '購入済み商品の説明',
            'price' => 3000,
            'image_path' => 'images/sold-item.jpg',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都テスト区1-2-3',
            'shipping_building' => null,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('購入済み商品');
        $response->assertSee('Sold');
    }

    public function test_own_item_is_not_displayed(): void
    {
        $loginUser = User::create([
            'name' => 'ログインユーザー',
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $otherUser = User::create([
            'name' => '別の出品者',
            'email' => 'other@example.com',
            'password' => 'password',
        ]);

        $conditionId = DB::table('conditions')->insertGetId([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Item::create([
            'user_id' => $loginUser->id,
            'condition_id' => $conditionId,
            'name' => '自分の出品商品',
            'description' => '自分の商品の説明',
            'price' => 1000,
            'image_path' => 'images/own-item.jpg',
        ]);

        Item::create([
            'user_id' => $otherUser->id,
            'condition_id' => $conditionId,
            'name' => '他人の出品商品',
            'description' => '他人の商品の説明',
            'price' => 2000,
            'image_path' => 'images/other-item.jpg',
        ]);

        $response = $this
            ->actingAs($loginUser)
            ->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の出品商品');
        $response->assertSee('他人の出品商品');
    }
}