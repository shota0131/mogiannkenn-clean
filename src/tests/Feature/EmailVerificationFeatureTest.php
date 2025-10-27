<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 会員登録後に認証メールが送信されることを確認
     */
    public function test_会員登録後に認証メールが送信される()
    {
        Notification::fake();

        
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        
        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /**
     * 認証誘導画面で「認証はこちらから」ボタンが表示されていること
     */
    public function test_認証誘導画面にボタンが表示されメール認証サイトに遷移できる()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        
        $response->assertStatus(200);

        
        $response->assertSee('認証はこちらから');

        
        $response->assertSee('https://mail.google.com/');
    }

    /**
     * メール認証完了後、プロフィール設定画面にリダイレクトされること
     */
    public function test_メール認証完了後プロフィール設定画面に遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        
        $response = $this->actingAs($user)->get($verificationUrl);

        
        $response->assertRedirect(route('profile.edit'));

        
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
