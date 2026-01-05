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
}
