<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_comment(): void
    {
        [$user, $item] = $this->createTestData();

        $response = $this
            ->actingAs($user)
            ->post(route('comments.store', [
                'itemId' => $item->id,
            ]), [
                'content' => 'テストコメントです',
            ]);

        $response->assertRedirect(route('items.show', [
            'itemId' => $item->id,
        ]));

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメントです',
        ]);

        $detailResponse = $this->get(route('items.show', [
            'itemId' => $item->id,
        ]));

        $detailResponse->assertSee('テストコメントです');

        $detailResponse->assertViewHas(
            'item',
            fn ($displayedItem) =>
                $displayedItem->comments_count === 1
        );
    }

    public function test_guest_cannot_comment(): void
    {
        [, $item] = $this->createTestData();

        $response = $this->post(route('comments.store', [
            'itemId' => $item->id,
        ]), [
            'content' => 'ゲストのコメント',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_is_required(): void
    {
        [$user, $item] = $this->createTestData();

        $response = $this
            ->actingAs($user)
            ->post(route('comments.store', [
                'itemId' => $item->id,
            ]), [
                'content' => '',
            ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください',
        ]);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_must_not_exceed_255_characters(): void
    {
        [$user, $item] = $this->createTestData();

        $response = $this
            ->actingAs($user)
            ->post(route('comments.store', [
                'itemId' => $item->id,
            ]), [
                'content' => str_repeat('あ', 256),
            ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以内で入力してください',
        ]);

        $this->assertDatabaseCount('comments', 0);
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