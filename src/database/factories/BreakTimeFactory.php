<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreakTimeFactory extends Factory
{
    public function definition()
    {
        return [
            'attendance_id' => Attendance::factory(),
            'start_time' => now()->subMinutes(30),
            'end_time' => now(),
        ];
    }
}
