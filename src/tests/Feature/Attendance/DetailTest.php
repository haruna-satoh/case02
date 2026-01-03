<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class DetailTest extends TestCase
{
    public function test_勤怠詳細画面の「名前」がログインユーザーの氏名になってる() {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::create(2026,1,1),
        ]);

        $response = $this->actingAs($user)->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
    }

    public function test_勤怠詳細画面の「日付」が選択した日付になってる() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::create(2026,1,1),
        ]);

        $response = $this->actingAs($user)->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);
        $response->assertSee('2026年01月01日');
    }

    public function test_「出勤・退勤」の時間が、ログインユーザーの打刻と一致している() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => "09:00",
            'end_time' => "17:00",
        ]);

        $response = $this->actingAs($user)->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);
        $response->assertSee('09:00');
        $response->assertSee('17:00');
    }

    public function test_「休憩」の時間が、ログインユーザーの打刻と一致している() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'start_time' => "09:00",
            'end_time' => "17:00",
        ]);

        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'start_time' => '12:00',
            'end_time' => '12:30',
        ]);

        $response = $this->actingAs($user)->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);
        $response->assertSee('12:00');
        $response->assertSee('12:30');
    }
}
