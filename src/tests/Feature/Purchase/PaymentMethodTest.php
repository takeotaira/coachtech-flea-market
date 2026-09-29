<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_payment_method_is_displayed_in_summary(): void
    {
        $user = User::forceCreate([
            'name' => '購入者',
            'email' => 'buyer@example.com',
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
            'name' => '購入対象商品',
            'description' => '購入対象商品の説明',
            'price' => 5000,
            'image_path' => 'images/item.jpg',
        ]);

        $response = $this
            ->actingAs($user)
            ->withSession([
                '_old_input' => [
                    'payment_method' => 'カード支払い',
                ],
            ])
            ->get(route('purchases.create', [
                'itemId' => $item->id,
            ]));

        $response->assertStatus(200);

        $content = $response->getContent();

        $this->assertMatchesRegularExpression(
            '/value="カード支払い"\s+selected/',
            $content
        );

        $this->assertMatchesRegularExpression(
            '/id="selected-payment-method">\s*カード支払い\s*<\/dd>/',
            $content
        );
    }
}