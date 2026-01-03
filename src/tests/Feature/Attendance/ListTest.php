<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class ListTest extends TestCase
{
    public function test_自分が行った勤怠情報が全て表示される() {
        $user = User::factory()->create();

        $attendances = Attendance::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertStatus(200);

        foreach ($attendances as $attendance) {
            $date = \Carbon\Carbon::parse($attendance->date)->format('m/d');
            $response->assertSee($date);
        }
    }

    public function test_勤怠一覧画面に遷移した際、現在の月が表示される() {
        $user = User::factory()->create();

        Carbon::setTestNow(Carbon::create(2026,1,3,9,0));

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertStatus(200);
        $response->assertSee('2026/01');
    }

    public function test_「前月」を押下した時、表示月の前月の情報が表示される() {
        Carbon::setTestNow(Carbon::create(2026,1,1));

        $user = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::create(2025,12,10),
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2025-12');

        $response->assertStatus(200);
        $response->assertSee('2025/12');

    }

    public function test_「翌月」を押下した時、表示月の翌月の情報が表示される() {
        Carbon::setTestNow(Carbon::create(2026,1,1));

        $user = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::create(2026,2,10),
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2026-02');

        $response->assertStatus(200);
        $response->assertSee('2026/02');
    }

    public function test_「詳細」を押下した時、その日の勤怠詳細画面に遷移する() {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::create(2026,1,1),
        ]);

        $response = $this->actingAs($user)->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);
        $response->assertSee('2026年01月01日');
    }
}
