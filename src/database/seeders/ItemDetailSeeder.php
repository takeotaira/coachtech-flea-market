<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Purchase;

class ItemDetailSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            [
                'email' => 'test@example.com',
            ],
            [
                'name' => 'テストユーザー',
                'password' => Hash::make('password123'),
            ]
        );

        $condition = Condition::firstOrCreate([
            'name' => '良好',
        ]);

        $category1 = Category::firstOrCreate([
            'name' => 'ファッション',
        ]);

        $category2 = Category::firstOrCreate([
            'name' => 'メンズ',
        ]);

        $item = Item::updateOrCreate(
            [
                'name' => 'テスト商品',
                'user_id' => $user->id,
            ],
            [
                'condition_id' => $condition->id,
                'brand_name' => 'テストブランド',
                'description' => '商品詳細画面確認用の商品です。',
                'price' => 5000,
                'image_path' => 'test.jpg',
            ]
        );

        $item->categories()->sync([
            $category1->id,
            $category2->id,
        ]);

        $buyer = User::firstOrCreate(
            [
                'email' => 'buyer@example.com',
            ],
            [
                'name' => '購入テストユーザー',
                'password' => Hash::make('password123'),
            ]
        );

        Like::firstOrCreate([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        Comment::firstOrCreate(
            [
                'user_id' => $buyer->id,
                'item_id' => $item->id,
                'content' => 'テストコメントです。',
            ]
        );

        Purchase::updateOrCreate(
            [
                'item_id' => $item->id,
            ],
            [
                'user_id' => $buyer->id,
                'payment_method' => 'コンビニ払い',
                'shipping_postal_code' => '123-4567',
                'shipping_address' => '東京都テスト区1-2-3',
                'shipping_building' => 'テストマンション101',
            ]
        );
    }
}