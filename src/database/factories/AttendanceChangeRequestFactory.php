<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AttendanceChangeRequest;
use App\Models\Attendance;
use App\Models\User;

class AttendanceChangeRequestFactory extends Factory
{
    protected $model = AttendanceChangeRequest::class;

    public function definition()
    {
        return [
            'attendance_id' => Attendance::factory(),
            'user_id' => User::factory(),
            'start_time' => '09:00:00',
            'end_time' => '17:30:00',
            'note' => 'テスト修正申請',
            'status' => '承認待ち',
        ];
    }
}
