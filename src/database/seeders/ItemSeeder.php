<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ItemSeeder extends Seeder
{
    private const ITEMS = [
        [
            'name' => '腕時計',
            'price' => 15000,
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'categories' => ['ファッション', 'メンズ', 'アクセサリー'],
            'condition_name' => '良好',
        ],
        [
            'name' => 'HDD',
            'price' => 5000,
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'categories' => ['家電'],
            'condition_name' => '目立った傷や汚れなし',
        ],
        [
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand_name' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'categories' => ['食品'],
            'condition_name' => 'やや傷や汚れあり',
        ],
        [
            'name' => '革靴',
            'price' => 4000,
            'brand_name' => null,
            'description' => 'クラシックなデザインの革靴',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'categories' => ['ファッション', 'メンズ'],
            'condition_name' => '状態が悪い',
        ],
        [
            'name' => 'ノートPC',
            'price' => 45000,
            'brand_name' => null,
            'description' => '高性能なノートパソコン',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'categories' => ['家電'],
            'condition_name' => '良好',
        ],
        [
            'name' => 'マイク',
            'price' => 8000,
            'brand_name' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'categories' => ['家電'],
            'condition_name' => '目立った傷や汚れなし',
        ],
        [
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand_name' => null,
            'description' => 'おしゃれなショルダーバッグ',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'categories' => ['ファッション'],
            'condition_name' => 'やや傷や汚れあり',
        ],
        [
            'name' => 'タンブラー',
            'price' => 500,
            'brand_name' => 'なし',
            'description' => '使いやすいタンブラー',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'categories' => ['キッチン'],
            'condition_name' => '状態が悪い',
        ],
        [
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand_name' => 'Starbucks',
            'description' => '手動のコーヒーミル',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'categories' => ['キッチン'],
            'condition_name' => '良好',
        ],
        [
            'name' => 'メイクセット',
            'price' => 2500,
            'brand_name' => null,
            'description' => '便利なメイクアップセット',
            'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            'categories' => ['コスメ', 'レディース'],
            'condition_name' => '目立った傷や汚れなし',
        ],
    ];

    public function run(): void
    {
        $user = User::where('email', 'seller@example.com')->first();

        if (!$user) {
            throw new RuntimeException('商品を登録するためのユーザーが存在しません。');
        }

        DB::transaction(function () use ($user) {
            foreach (self::ITEMS as $itemData) {
                $this->seedItem($user, $itemData);
            }
        });
    }

    private function seedItem(User $user, array $itemData): void
    {
        $condition = Condition::where(
            'name',
            $itemData['condition_name']
        )->firstOrFail();

        $item = Item::updateOrCreate(
            [
                'name' => $itemData['name'],
            ],
            [
                'user_id' => $user->id,
                'condition_id' => $condition->id,
                'brand_name' => $itemData['brand_name'],
                'description' => $itemData['description'],
                'price' => $itemData['price'],
                'image_path' => $itemData['image_path'],
            ]
        );

        $categoryIds = Category::whereIn(
            'name',
            $itemData['categories']
        )->pluck('id');

        $item->categories()->sync($categoryIds);
    }
}