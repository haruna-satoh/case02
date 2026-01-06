<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Attendance;
use Carbon\Carbon;

class StaffTest extends TestCase
{
    public function test_管理者が全一般ユーザーの「氏名」「メールアドレス」を確認できる() {
        $admin = Admin::factory()->create();

        $user1 = User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test1@example.com',
        ]);

        $user2 =User::factory()->create([
            'name' => 'テスト花子',
            'email' => 'test2@example.com',
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/staff/list');

        $response->assertStatus(200);

        $response->assertSee('テスト太郎');
        $response->assertSee('テスト花子');

        $response->assertSee('test1@example.com');
        $response->assertSee('test2@example.com');
    }

    public function test_ユーザーの勤怠情報が正しく表示される() {
        $admin = Admin::factory()->create();

        $user = User::factory()->create([
            'name' => 'テスト太郎',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-05',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/staff/{$user->id}");

        $response->assertStatus(200);

        $response->assertSee('テスト太郎');
        $response->assertSee('01/05');
        $response->assertSee('09:00');
        $response->assertSee('17:00');
    }

    public function test_「前月」を押下した時、表示月の前月が表示される() {
        Carbon::setTestNow(Carbon::create(2026,1,1));

        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::create(2025,12,10),
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/staff/{$user->id}?month=2025-12");

        $response->assertStatus(200);
        $response->assertSee('2025/12');
    }

    public function test_「翌月」を押下した時、表示月の翌月が表示される() {
        Carbon::setTestNow(Carbon::create(2026,1,1));

        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::create(2026,2,10),
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/staff/{$user->id}?month=2025-2");

        $response->assertStatus(200);
        $response->assertSee('2025/02');
    }

    public function test_「詳細」を押下すると、その日の勤怠詳細画面に遷移する() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-05',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/staff/{$user->id}");

        $response->assertStatus(200);

        $response->assertSee("/admin/attendance/{$attendance->id}");
    }
}
