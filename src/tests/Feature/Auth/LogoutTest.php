<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('items.index'));
    }
}