<?php

namespace Tests\Feature\Item;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_item_details_are_displayed(): void
    {
        $seller = $this->createUser(
            '出品者',
            'seller@example.com'
        );

        $commentUser = $this->createUser(
            'コメントユーザー',
            'comment@example.com'
        );

        $conditionId = $this->createCondition();

        $category = Category::create([
            'name' => '家電',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => 'テストカメラ',
            'brand_name' => 'テストブランド',
            'description' => '商品の詳しい説明です',
            'price' => 12000,
            'image_path' => 'images/camera.jpg',
        ]);

        $item->categories()->attach($category->id);

        Like::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'content' => 'テストコメントです',
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('images/camera.jpg');
        $response->assertSee('テストカメラ');
        $response->assertSee('テストブランド');
        $response->assertSee('12,000');
        $response->assertSee('商品の詳しい説明です');
        $response->assertSee('家電');
        $response->assertSee('良好');
        $response->assertSee('コメント（1）');
        $response->assertSee('コメントユーザー');
        $response->assertSee('テストコメントです');

        $response->assertViewHas(
            'item',
            fn ($displayedItem) =>
                $displayedItem->likes_count === 1
                && $displayedItem->comments_count === 1
        );
    }

    public function test_multiple_categories_are_displayed(): void
    {
        $seller = $this->createUser(
            '出品者',
            'seller@example.com'
        );

        $conditionId = $this->createCondition();

        $categoryOne = Category::create([
            'name' => '家電',
        ]);

        $categoryTwo = Category::create([
            'name' => 'ファッション',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => '複数カテゴリ商品',
            'brand_name' => null,
            'description' => '複数カテゴリ商品の説明',
            'price' => 5000,
            'image_path' => 'images/item.jpg',
        ]);

        $item->categories()->attach([
            $categoryOne->id,
            $categoryTwo->id,
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('家電');
        $response->assertSee('ファッション');
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
}