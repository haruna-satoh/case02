<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールアドレスが未入力の場合、バリデーションエラーが表示される() {
        $response = $this->post('/login', [
            'email' => 'no_user@example.com',
            'password' => 'password123',
        ]);

        $formData = [
            'email' => '',
            'password' => 'password123',
        ];

        $response = $this->post('/login', $formData);

        $response->assertSessionHasErrors(['email']);

        $this->assertStringContainsString(
            'メールアドレスを入力してください',
            session('errors')->first('email')
        );
    }

    public function test_パスワードが未入力の場合、バリデーションエラーが表示される() {
        $response = $this->post('/login', [
            'email' => 'no_user@example.com',
            'password' => 'password123',
        ]);

        $formData = [
            'email' => 'no_user@example.com',
            'password' => ''
        ];

        $response = $this->post('/login', $formData);

        $response->assertSessionHasErrors(['password']);

        $this->assertStringContainsString(
            'パスワードを入力してください',
            session('errors')->first('password')
        );
    }

    public function test_登録情報と一致しない場合、バリデーションエラーが表示される() {
        $response = $this->post('/login', [
            'email' => 'no_user@example.com',
            'password' => 'password123',
        ]);

        $formDate = [
            'email' => 'no_user2@example.com',
            'password' => 'password123'
        ];

        $response->assertSessionHasErrors(['login']);

        $this->assertStringContainsString(
            'ログイン情報が登録されていません',
            session('errors')->first('login')
        );
    }
}
