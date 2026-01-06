<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\AttendanceChangeRequest as AttendanceChangeRequestModels;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_管理者の申請一覧と承認画面に承認待ちの申請が表示される() {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
        ]);

        $changeRequest = AttendanceChangeRequestModels::factory()->create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'status' => '承認待ち',
            'note' => 'テスト申請',
        ]);

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/stamp_correction_request/list');

        $response->assertStatus(200);

        $response->assertSee('承認待ち');
        $response->assertSee('テスト太郎');
        $response->assertSee('テスト申請');

        $response = $this->actingAs($admin, 'admin')->get("/admin/stamp_correction_request/approve/{$changeRequest->id}");

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('テスト申請');
    }

    public function test_承認済みの修正申請が全て表示されている() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        AttendanceChangeRequestModels::factory()->create([
            'attendance_id' => $attendance->id,
            'status' => '承認済み',
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/stamp_correction_request/list?tab=approved');

        $response->assertStatus(200);
        $response->assertSee('承認済み');
    }

    public function test_修正申請の詳細内容が正しく表示される() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $request = AttendanceChangeRequestModels::factory()->create([
            'attendance_id' => $attendance->id,
            'start_time' => '10:00',
            'end_time' => '18:00',
            'note' => '修正理由テスト',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/stamp_correction_request/approve/{$request->id}");

        $response->assertStatus(200);
        $response->assertSee('10:00');
        $response->assertSee('18:00');
        $response->assertSee('修正理由テスト');
    }

    public function test_修正申請の承認処理が正しく行われる() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $request = AttendanceChangeRequestModels::factory()->create([
            'attendance_id' => $attendance->id,
            'start_time' =>'10:00',
            'end_time' => '18:00',
            'status' => '承認待ち',
        ]);

        $response = $this->actingAs($admin, 'admin')->patch("/admin/stamp_correction_request/{$request->id}/approve", [
            'start_time' => '10:00',
            'end_time' => '18:00',
            'note' => '承認テスト',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'start_time' => '10:00:00',
            'end_time' => '18:00:00',
        ]);

        $this->assertDatabaseHas('attendance_change_requests', [
            'id' => $request->id,
            'status' => '承認済み',
        ]);
    }
}
