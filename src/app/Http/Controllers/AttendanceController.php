<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('date',today())->first();

        if (!$attendance) {
            return redirect()->back();
        }

        $attendance->update([
            'end_time' => Carbon::now()->format('H:i'),
            'status' => '退勤済',
        ]);

        return redirect()->back();
    }

    public function breakStart() {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('date', now()->toDateString())->firstOrFail();

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'start_time' => now()->format('H:i'),
        ]);

        $attendance->update([
            'status' => '休憩中',
        ]);

        return redirect()->back();
    }

    public function breakEnd() {
        $attendance = Attendance::where('user_id', auth()->id())->whereDate('date', now()->toDateString())->firstOrFail();

        $break = BreakTime::where('attendance_id', $attendance->id)->whereNull('end_time')->latest()->firstOrFail();

        $break->update([
            'end_time' => now()->format('H:i'),
        ]);

        $attendance->update([
            'status' => '出勤中',
        ]);

        return redirect()->back();
    }

    public function list(Request $request) {
        $user = auth()->user();

        $month = $request->query('month') ? Carbon::createFromFormat('Y-m', $request->query('month')) : Carbon::now();

        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $dates = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $attendances = Attendance::where('user_id', $user->id)->whereBetween('date', [$startOfMonth, $endOfMonth])->get()->keyBy('date');

        return view('attendance.list', compact('dates', 'attendances', 'month'));
    }

    public function show($id) {
        $attendance = Auth::user()->attendances()->findOrFail($id);

        return view('attendance.show', compact('attendance'));
    }
}
