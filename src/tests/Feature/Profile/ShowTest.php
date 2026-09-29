<?php

namespace Tests\Feature\Profile;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_and_selling_items_are_displayed(): void
    {
        $user = $this->createUser(
            'テストユーザー',
            'user@example.com'
        );

        DB::table('profiles')->insert([
            'user_id' => $user->id,
            'profile_image' => 'storage/profiles/user.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-2-3',
            'building' => 'テストビル101',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $item = $this->createItem(
            $user,
            '出品した商品'
        );

        $response = $this
            ->actingAs($user)
            ->get(route('mypage', [
                'page' => 'sell',
            ]));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('storage/profiles/user.jpg');
        $response->assertSee($item->name);
    }

    public function test_purchased_items_are_displayed(): void
    {
        $buyer = $this->createUser(
            '購入者',
            'buyer@example.com'
        );

        $seller = $this->createUser(
            '出品者',
            'seller@example.com'
        );

        $item = $this->createItem(
            $seller,
            '購入した商品'
        );

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都テスト区1-2-3',
            'shipping_building' => 'テストビル101',
        ]);

        $response = $this
            ->actingAs($buyer)
            ->get(route('mypage', [
                'page' => 'buy',
            ]));

        $response->assertStatus(200);
        $response->assertSee('購入者');
        $response->assertSee($item->name);
    }

    private function createUser(
        string $name,
        string $email
    ): User {
        return User::forceCreate([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);
    }

    private function createItem(
        User $user,
        string $name
    ): Item {
        $conditionId = DB::table('conditions')->insertGetId([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Item::create([
            'user_id' => $user->id,
            'condition_id' => $conditionId,
            'name' => $name,
            'description' => $name . 'の説明',
            'price' => 5000,
            'image_path' => 'images/item.jpg',
        ]);
    }
}