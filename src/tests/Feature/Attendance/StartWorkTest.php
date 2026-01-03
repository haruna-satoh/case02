<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class StartWorkTest extends TestCase
{
    public function test_出勤ボタンが正しく機能する() {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/attendance')->assertStatus(200)->assertSee('出勤');

        $this->actingAs($user)->post('/attendance/start');

        $this->actingAs($user)->get('/attendance')->assertSee('出勤中');
    }

    public function test_出勤は一日一回のみできる() {
        $user = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => today(),
            'status' => '退勤済',
        ]);

        $this->actingAs($user)->get('/attendance')->assertStatus(200)->assertDontSee('attendance/start');
    }

    public function test_出勤時刻が勤怠一覧画面で確認できる() {
        Carbon::setTestNow(Carbon::create(2026,1,3,9,39));

        $user = User::factory()->create();

        $this->actingAs($user)->post('/attendance/start');

        $this->actingAs($user)->get('/attendance/list')->assertStatus(200)->assertSee('09:39');
    }
}
