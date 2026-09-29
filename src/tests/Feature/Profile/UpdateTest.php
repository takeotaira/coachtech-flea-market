<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_profile_information_is_displayed(): void
    {
        $user = User::forceCreate([
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        DB::table('profiles')->insert([
            'user_id' => $user->id,
            'profile_image' => 'storage/profiles/user.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-2-3',
            'building' => 'テストビル101',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('mypage.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('storage/profiles/user.jpg');
        $response->assertSee('value="テストユーザー"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee(
            'value="東京都テスト区1-2-3"',
            false
        );
        $response->assertSee(
            'value="テストビル101"',
            false
        );
    }
}