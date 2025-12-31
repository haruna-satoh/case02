<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前が未入力の場合、バリデーションエラーになる() {
        $formData = [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $formData);

        $response->assertSessionHasErrors(['name']);

        $this->assertStringContainsString(
            'お名前を入力してください',
            session('errors')->first('name')
        );
    }

    public function test_メールアドレスが未入力の場合、バリデーションエラーになる() {
        $formData = [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $formData);

        $response->assertSessionHasErrors(['email']);

        $this->assertStringContainsString(
            'メールアドレスを入力してください',
            session('errors')->first('email')
        );
    }

    public function test_パスワードが8文字未満の場合、バリデーションエラーになる() {
        $formData = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'pass12',
            'password_confirmation' => 'pass12',
        ];

        $response = $this->post('/register', $formData);

        $response->assertSessionHasErrors(['password']);

        $this->assertStringContainsString(
            'パスワードは8文字以上で入力してください',
            session('errors')->first('password')
        );
    }

    public function test_パスワードが一致しない場合、バリデーションエラーになる() {
        $formData = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password12'
        ];

        $response = $this->post('/register', $formData);

        $response->assertSessionHasErrors(['password']);

        $this->assertStringContainsString(
            'パスワードと一致しません',
            session('errors')->first('password')
        );
    }

    public function test_パスワードが未入力の場合、バリデーションエラーになる() {
        $formData = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->post('/register', $formData);

        $response->assertSessionHasErrors(['password']);

        $this->assertStringContainsString(
            'パスワードを入力してください',
            session('errors')->first('password')
        );
    }

    public function test_フォームに内容が入力されていた場合、データが正常に保存される() {
        $formData = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $formData);

        $this->assertDatabaseHas('users', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
        ]);
    }
}
