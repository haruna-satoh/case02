<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::now()->subDays($i);
                $clockIn = $date->copy()->setTime(rand(8, 9), rand(0, 59));
                $clockOut = $clockIn->copy()->addHours(rand(7, 9));

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->toDateString(),
                    'start_time' => $clockIn,
                    'end_time' => $clockOut,
                ]);

                for ($j = 0; $j < rand(1, 2); $j++) {
                    $breakStart = $clockIn->copy()->addHours(rand(2, 4));
                    $breakEnd = $breakStart->copy()->addMinutes(rand(30, 60));

                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'start_time' => $breakStart,
                        'end_time' => $breakEnd,
                    ]);
                }
            }
        }
    }
}
