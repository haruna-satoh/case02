<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class EndWorkTest extends TestCase
{
    public function test_退勤ボタンが正しく機能する() {
        $user = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'status' => '出勤中',
        ]);

        $this->actingAs($user)->get('/attendance')->assertStatus(200)->assertSee('退勤');

        $this->actingAs($user)->post('/attendance/end');

        $this->actingAs($user)->get('attendance')->assertSee('退勤済');
    }

    public function test_退勤時刻が勤怠一覧画面で確認できる() {
        $user = User::factory()->create();

        Carbon::setTestNow(Carbon::create(2026,1,3,9,0));
        $this->actingAs($user)->post('attendance/start');

        Carbon::setTestNow(Carbon::create(2026,1,3,17,0));
        $this->actingAs($user)->post('/attendance/end');

        $this->actingAs($user)->get('/attendance/list')->assertStatus(200)->assertSee('17:00');
    }
}
