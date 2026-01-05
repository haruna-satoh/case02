<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class ListTest extends TestCase
{
    public function test_その日の全ユーザーの勤怠情報が確認できる() {
        $admin = Admin::factory()->create([
            'name' => '管理者太郎',
        ]);

        $user = User::factory()->create(['name' => 'テスト太郎']);

        $testDate = '2026-01-05';

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => $testDate,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/list?selected_date={$testDate}");

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee($testDate);
    }

    public function test_遷移した際に現在の日付が表示される() {
        $admin = Admin::factory()->create([
            'name' => '管理者太郎',
        ]);

        $testDate = '2026-01-03';

        $attendance = Attendance::factory()->create([
            'date' => $testDate,
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/list?selected_date={$testDate}");

        $response->assertStatus(200);
        $response->assertSee($testDate);
    }

    public function test_「前日」を押下した時、前の日の勤怠情報が表示される() {
        $admin = Admin::factory()->create([
            'name' => '管理者太郎',
        ]);

        $dayBefore = '2026-01-02';
        $today = '2026-01-03';

        $attendanceBefore = Attendance::factory()->create(['date' => $dayBefore]);
        $attendanceToday = Attendance::factory()->create(['date' => $today]);

        $response = $this->actingAs($admin, 'admin')->get("admin/attendance/list?nav={$dayBefore}");

        $response->assertStatus(200);
        $response->assertSee($dayBefore);
    }

    public function test_「翌日」を押下した時、前の日の勤怠情報が表示される() {
        $admin = Admin::factory()->create([
            'name' => '管理者太郎',
        ]);

        $today = '2026-01-03';
        $dayNext = '2026-01-04';

        $attendanceToday = Attendance::factory()->create(['date' => $today]);
        $attendanceNext = Attendance::factory()->create(['date' => $dayNext]);

        $response = $this->actingAs($admin, 'admin')->get("admin/attendance/list?nav={$dayNext}");

        $response->assertStatus(200);
        $response->assertSee($dayNext);
    }
}
