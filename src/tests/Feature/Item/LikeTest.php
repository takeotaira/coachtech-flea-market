<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_item(): void
    {
        [$user, $item] = $this->createTestData();

        $response = $this
            ->actingAs($user)
            ->post(route('likes.store', [
                'itemId' => $item->id,
            ]));

        $response->assertRedirect(route('items.show', [
            'itemId' => $item->id,
        ]));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $detailResponse = $this->get(route('items.show', [
            'itemId' => $item->id,
        ]));

        $detailResponse->assertViewHas(
            'item',
            fn ($displayedItem) =>
                $displayedItem->likes_count === 1
        );
    }

    public function test_liked_icon_changes_color(): void
    {
        [$user, $item] = $this->createTestData();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('items.show', [
                'itemId' => $item->id,
            ]));

        $response->assertStatus(200);
        $response->assertSee('images/heart-pink.png');
        $response->assertDontSee('images/heart-default.png');
    }

    public function test_user_can_remove_like(): void
    {
        [$user, $item] = $this->createTestData();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('likes.destroy', [
                'itemId' => $item->id,
            ]));

        $response->assertRedirect(route('items.show', [
            'itemId' => $item->id,
        ]));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $detailResponse = $this->get(route('items.show', [
            'itemId' => $item->id,
        ]));

        $detailResponse->assertViewHas(
            'item',
            fn ($displayedItem) =>
                $displayedItem->likes_count === 0
        );
    }

    private function createTestData(): array
    {
        $user = User::forceCreate([
            'name' => 'ログインユーザー',
            'email' => 'user@example.com',
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
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'image_path' => 'images/item.jpg',
        ]);

        return [$user, $item];
    }
}