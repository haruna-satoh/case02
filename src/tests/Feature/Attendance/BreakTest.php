<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class BreakTest extends TestCase
{
    public function test_休憩ボタンが正しく機能する() {
        $user = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'status' => '出勤中',
        ]);

        $this->actingAs($user)->get('/attendance')->assertStatus(200)->assertSee('休憩入');

        $this->actingAs($user)->post('/attendance/break/start');

        $this->actingAs($user)->get('attendance')->assertSee('休憩中');
    }

    public function test_休憩は1日に何回もできる() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'status' => '出勤中',
        ]);

        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'start_time' => '12:00',
            'end_time' => '12:30',
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('休憩入');
    }

    public function test_休憩戻ボタンが正しく機能する() {
        $user =User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'status' => '出勤中',
        ]);

        $this->actingAs($user)->post('/attendance/break/start');

        $this->actingAs($user)->get('/attendance')->assertStatus(200)->assertSee('休憩戻');
    }

    public function test_休憩戻は1日に何回もできる() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'status' => '出勤中',
        ]);

        $this->actingAs($user)->post('/attendance/break/start');
        $this->actingAs($user)->post('attendance/break/end');

        $this->actingAs($user)->post('/attendance/break/start');

        $this->actingAs($user)->get('/attendance')->assertStatus(200)->assertSee('休憩戻');
    }

    public function test_休憩時刻が勤怠一覧画面で確認できる() {
        $user = User::factory()->create();

        Carbon::setTestNow(Carbon::create(2026,1,3,9,0));
        $this ->actingAs($user)->post('/attendance/start');

        Carbon::setTestNow(Carbon::create(2026,1,3,12,0));
        $this->actingAs($user)->post('/attendance/break/start');

        Carbon::setTestNow(Carbon::create(2026,1,3,13,0));
        $this->actingAs($user)->post('/attendance/break/end');

        $this->actingAs($user)->get('/attendance/list')->assertStatus(200)->assertSee('1:00');
    }
}
