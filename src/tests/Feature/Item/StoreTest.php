<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_information_can_be_stored(): void
    {
        Storage::fake('public');

        $user = User::forceCreate([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'ファッション',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $conditionId = DB::table('conditions')->insertGetId([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('items.store'), [
                'image' => UploadedFile::fake()->createWithContent(
                    'item.png',
                    base64_decode(
                        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwC'
                        . 'AAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
                    )
                ),
                'categories' => [$categoryId],
                'condition_id' => $conditionId,
                'name' => 'テスト商品',
                'brand_name' => 'テストブランド',
                'description' => 'テスト商品の説明',
                'price' => 5000,
            ]);

        $item = Item::where('name', 'テスト商品')->firstOrFail();

        $response->assertRedirect(route('items.show', [
            'itemId' => $item->id,
        ]));

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'user_id' => $user->id,
            'condition_id' => $conditionId,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 5000,
        ]);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $categoryId,
        ]);

        Storage::disk('public')->assertExists(
            str_replace('storage/', '', $item->image_path)
        );
    }
}