<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_勤務外の場合、ステータスが勤務外と表示される() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('勤務外');
    }

    public function test_出勤中の場合、ステータスが出勤中と表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => now(),
            'end_time' => null,
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('出勤中');
    }

    public function test_休憩中の場合、ステータスが休憩中と表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->for($user)->create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => now(),
            'end_time' => null,
            'status' => '休憩中',
        ]);

        $attendance->breakTimes()->create([
            'date' => Carbon::today(),
            'start_time' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('休憩中');
    }

    public function test_退勤済みの場合、ステータスが退勤済と表示される() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->for($user)->create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' =>now(),
            'end_time' => now(),
            'status' => '退勤済'
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('退勤済');
    }
}
