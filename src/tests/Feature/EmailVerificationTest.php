<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録後、認証メールが送信される() {
        Notification::fake();

        $userDate = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userDate);

        $response->assertStatus(302);

        $response->assertRedirect('/email/verify');

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    public function test_メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する() {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/email/verify');

        $response->assertStatus(200);
        $response->assertSee('認証はこちらから');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $verifyResponse = $this->get($verificationUrl);

        $verifyResponse->assertStatus(302);
        $verifyResponse->assertRedirect('/attendance');
    }

    public function test_メール認証を完了すると、勤怠登録画面に遷移する() {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $this->get($verificationUrl);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => now(),
        ]);

        $response = $this->get('/email/verify');

        $response->assertStatus(302);
        $response->assertRedirect('/attendance');

        $attendancePage = $this->followRedirects($response);
        $attendancePage->assertStatus(200);
        $attendancePage->assertSee('出勤');
        $attendancePage->assertSee('勤務外');
    }
}
