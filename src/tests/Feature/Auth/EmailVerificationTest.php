<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_email_is_sent_after_registration(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where(
            'email',
            'user@example.com'
        )->firstOrFail();

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );

        $response->assertRedirect(
            route('verification.notice')
        );
    }

    public function test_verification_notice_links_to_mail_site(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this
            ->actingAs($user)
            ->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertSee('認証はこちらから');
        $response->assertSee(
            'href="http://localhost:8025"',
            false
        );
    }

    public function test_verified_user_is_redirected_to_profile_setting(): void
    {
        Event::fake();

        $user = $this->createUnverifiedUser();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this
            ->actingAs($user)
            ->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue(
            $user->fresh()->hasVerifiedEmail()
        );

        $response->assertRedirect(
            route('mypage.profile.edit')
        );
    }

    private function createUnverifiedUser(): User
    {
        return User::forceCreate([
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => 'password',
            'email_verified_at' => null,
        ]);
    }
}