<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_items_can_be_searched_by_partial_name(): void
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
            'name' => '高級腕時計',
            'description' => '腕時計の説明',
            'price' => 10000,
            'image_path' => 'images/watch.jpg',
        ]);

        Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => '革靴',
            'description' => '革靴の説明',
            'price' => 5000,
            'image_path' => 'images/shoes.jpg',
        ]);

        $response = $this->get('/?keyword=時計');

        $response->assertStatus(200);
        $response->assertSee('高級腕時計');
        $response->assertDontSee('革靴');
    }

    public function test_search_keyword_is_retained_in_mylist(): void
    {
        $user = User::create([
            'name' => 'ログインユーザー',
            'email' => 'user@example.com',
            'password' => 'password',
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

        $matchingItem = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => '高級腕時計',
            'description' => '腕時計の説明',
            'price' => 10000,
            'image_path' => 'images/watch.jpg',
        ]);

        $nonMatchingItem = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => '革靴',
            'description' => '革靴の説明',
            'price' => 5000,
            'image_path' => 'images/shoes.jpg',
        ]);

        DB::table('likes')->insert([
            [
                'user_id' => $user->id,
                'item_id' => $matchingItem->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'item_id' => $nonMatchingItem->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist&keyword=時計');

        $response->assertStatus(200);
        $response->assertSee('高級腕時計');
        $response->assertDontSee('革靴');
        $response->assertSee('value="時計"', false);
    }
}