<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index() {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        return view('attendance.index', compact('attendance'));
    }

    public function start() {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'status' => '出勤中',
                'start_time' => Carbon::now()->format('H:i'),
            ]
        );

        if ($attendance->start_time === null) {
            $attendance->update([
                'start_time' => Carbon::now()->format('H:i'),
                'status' => '出勤中',
            ]);
        }

        return redirect()->route('attendance.index');
    }

    public function end() {
        return redirect()->back();
    }

    public function breakStart() {
        return redirect()->back();
    }
}
