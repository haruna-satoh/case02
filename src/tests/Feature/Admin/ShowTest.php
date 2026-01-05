<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Attendance;

class ShowTest extends TestCase
{
    public function test_勤怠詳細画面に選択したデータが表示される() {
        $admin = Admin::factory()->create(['name' => '管理者太郎']);
        $user = User::factory()->create(['name' => 'テスト太郎']);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-05',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/{$attendance->id}");

        $response->assertStatus(200);

        $response->assertSee('テスト太郎');
        $response->assertSee('2026年01月05日');
        $response->assertSee('09:00');
        $response->assertSee('17:00');
    }

    public function test_出勤時間が退勤時間より後になっている場合、バリデーションエラーが表示される() {
        $admin = Admin::factory()->create(['name' => '管理者太郎']);
        $user = User::factory()->create(['name' => 'テスト太郎']);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-05',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->patch("/admin/stamp_correction_request/{$attendance->id}/approve", [
            'start_time' => '18:00',
            'end_time' => '17:00',
            'note' => 'テスト修正申請',
            'breaks' => [],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('start_time');

        $this->assertStringContainsString(
            '出勤時間が不適切な値です',
            session('errors')->first('start_time')
        );
    }

    public function test_休憩開始時間が退勤時間より後になっている場合、バリデーションエラーになる() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-05',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->patch("/admin/stamp_correction_request/{$attendance->id}/approve", [
            'start_time' => '09:00',
            'end_time' => '17:00',
            'note' => 'テスト修正申請',
            'breaks' => [
                ['break_number' =>1, 'start_time' => '17:30', 'end_time' => '18:00'],
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('breaks.0.start_time');

        $this->assertStringContainsString(
            '休憩時間が不適切な値です',
            session('errors')->first('breaks.0.start_time')
        );
    }

    public function test_休憩終了時間が退勤時間より後になってる場合、バリデーションエラーが表示される() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-05',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->patch("/admin/stamp_correction_request/{$attendance->id}/approve", [
            'start_time' => '09:00',
            'end_time' => '17:00',
            'note' => 'テスト修正申請',
            'breaks' => [
                ['break_number' =>1, 'start_time' => '16:00', 'end_time' => '18:00'],
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('breaks.0.end_time');

        $this->assertStringContainsString(
            '休憩時間もしくは退勤時間が不適切な値です',
            session('errors')->first('breaks.0.end_time')
        );
    }

    public function test_備考欄が未入力の場合、バリデーションエラーが表示される() {
        $admin = Admin::factory()->create(['name' => '管理者太郎']);
        $user = User::factory()->create(['name' => 'テスト太郎']);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-05',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->patch("/admin/stamp_correction_request/{$attendance->id}/approve", [
            'start_time' => '18:00',
            'end_time' => '17:00',
            'note' => '',
            'breaks' => [],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('note');

        $this->assertStringContainsString(
            '備考を記入してください',
            session('errors')->first('note')
        );
    }
}
