<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Events\Verified;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{   
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function 未認証ユーザーはメール認証画面にリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $this->actingAs($user)
            ->get('/mypage')
            ->assertRedirect('/email/verify');
    }

    /** @test */
    public function メール認証完了後プロフィール画面にリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
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
