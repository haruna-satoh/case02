<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\Admin;
use App\Models\AttendanceChangeRequest as AttendanceChangeRequestModel;
use Carbon\Carbon;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $attendance;
    protected $changeRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();

        $this->user = User::factory()->create();

        $this->attendance = Attendance::factory()->create([
            'user_id' => $this->user->id,
            'date' => now(),
            'start_time' => '09:00',
            'end_time' => '18:00',
        ]);

        $this->changeRequest = AttendanceChangeRequestModel::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->user->id,
            'start_time' => '09:30',
            'end_time' => '18:30',
            'note' => 'テスト修正申請',
            'status' => '承認済み',
        ]);
    }

    public function test_出勤時間が退勤時間より後になっている場合、バリデーションエラーが表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($user)->post('/attendance/change/' . $attendance->id, [
            'start_time' => '18:00',
            'end_time' => '17:00',
            'note' => 'テスト修正申請',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('start_time');

        $this->assertStringContainsString(
            '出勤時間が不適切な値です',
            session('errors')->first('start_time')
        );
    }

    public function test_休憩時間が退勤時間より後になっている場合、バリデーションエラーが表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($user)->post('/attendance/change/' . $attendance->id, [
            'start_time' => '09:00',
            'end_time' => '17:00',
            'note' => 'テスト修正申請',
            'breaks' => [
                [
                    'break_number' => 1,
                    'start_time' => '18:00',
                    'end_time' => '18:30',
                ],
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['breaks.0.start_time',]);

        $this->assertStringContainsString(
            '休憩時間が不適切な値です',
            session('errors')->first('breaks.0.start_time')
        );
    }

    public function test_休憩終了時間が退勤時間より後になってる場合、バリデーションエラーが表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($user)->post('/attendance/change/' . $attendance->id, [
            'start_time' => '09:00',
            'end_time' => '17:00',
            'note' => 'テスト修正申請',
            'breaks' => [
                [
                    'break_number' => 1,
                    'start_time' => '16:30',
                    'end_time' => '18:30',
                ],
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['breaks.0.end_time',]);

        $this->assertStringContainsString(
            '休憩時間もしくは退勤時間が不適切な値です',
            session('errors')->first('breaks.0.end_time')
        );
    }

    public function test_備考欄が未入力の場合、バリデーションエラーが表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($user)->post('/attendance/change/' . $attendance->id, [
            'start_time' => '09:00',
            'end_time' => '17:00',
            'note' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('note');

        $this->assertStringContainsString(
            '備考を記入してください',
            session('errors')->first('note')
        );
    }

    public function test_修正申請処理が実行される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($user)->post('/attendance/change/' . $attendance->id, [
            'start_time' => '09:00',
            'end_time' => '17:30',
            'note' => 'テスト修正申請',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('attendance_change_requests', [
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'start_time' => '09:00:00',
            'end_time' => '17:30:00',
            'note' => 'テスト修正申請',
            'status' => '承認待ち',
        ]);

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'status' => '承認待ち',
        ]);
    }

    public function test_「承認待ち」にログインしたユーザーの申請が全て表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($user)->post('/attendance/change/' . $attendance->id, [
            'start_time' => '09:00',
            'end_time' => '17:30',
            'note' => 'テスト修正申請',
        ]);

        $response->assertStatus(302);

        $response = $this->actingAs($user)->get('/stamp_correction_request/list');

        $response->assertStatus(200);

        $response->assertSee('承認待ち');
        $response->assertSee('テスト修正申請');
    }

    public function test_「承認済み」に管理者が承認した修正申請が全て表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        AttendanceChangeRequestModel::factory()->create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'status' => '承認済み',
            'note' => 'テスト修正申請',
        ]);

        $attendance->update([
            'status' => '承認済み',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/stamp_correction_request/list?tab=approved');

        $response->assertStatus(200);
        $response->assertSee('承認済み');
        $response->assertSee('テスト修正申請');
    }

    public function test_「詳細」を押下すると勤怠詳細画面に遷移する() {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $this->actingAs($user);

        $response = $this->post("/attendance/change/{$attendance->id}", [
            'start_time' => '09:30',
            'end_time' => '18:00',
            'note' => 'テスト修正',
            'breaks' => [
                ['break_number' => 1, 'start_time' => '12:00', 'end_time' => '12:30'],
                ['break_number' => 2, 'start_time' => '15:00', 'end_time' => '15:15'],
            ],
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('attendance_change_requests', [
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'status' => '承認待ち',
            'note' => 'テスト修正',
        ]);

        $response = $this->get('/stamp_correction_request/list');
        $response->assertStatus(200);
        $response->assertSee('テスト修正');
        $response->assertSee('承認待ち');

        $changeRequest = AttendanceChangeRequestModel::where('attendance_id', $attendance->id)->first();

        $response = $this->get("/attendance/detail/{$attendance->id}");
        $response->assertStatus(200);
        $response->assertSee('勤怠詳細');
    }
}
