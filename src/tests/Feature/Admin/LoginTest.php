<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;

class LoginTest extends TestCase
{
    public function test_メールアドレスが未入力の場合、バリデーションエラーになる() {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);

        $this->assertStringContainsString(
            'メールアドレスを入力してください',
            session('errors')->first('email')
        );
    }

    public function test_パスワードが未入力の場合、バリデーションエラーになる() {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

        $this->assertStringContainsString(
            'パスワードを入力してください',
            session('errors')->first('password')
        );
    }

    public function test_登録内容と一致しない場合、バリデーションエラーになる() {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response =$this->post('/admin/login', [
            'email' => 'no_user@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');

        $this->assertStringContainsString(
            'ログイン情報が登録されていません',
            session('errors')->first('login')
        );
    }
}
