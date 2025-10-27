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

        // 会員登録を実行
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 登録完了後は認証メール送信ページへ遷移
        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        // 認証メールが送信されていることを確認
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /**
     * 認証誘導画面で「認証はこちらから」ボタンが表示されていること
     */
    public function test_認証誘導画面にボタンが表示されメール認証サイトに遷移できる()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        // ページが表示されること
        $response->assertStatus(200);

        // 「認証はこちらから」ボタンが存在すること
        $response->assertSee('認証はこちらから');

        // ボタンのリンク先が Gmail に設定されていること（UI的仕様）
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

        // 有効な署名付きURLを生成
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // 認証リンクにアクセス
        $response = $this->actingAs($user)->get($verificationUrl);

        // プロフィール編集画面へリダイレクトされること
        $response->assertRedirect(route('profile.edit'));

        // メールが認証済みになっていること
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
