<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceChangeRequest as AttendanceChangeRequestModels;
use App\Models\BreakTime;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AttendanceChangeRequest;

class AttendanceChangeController extends Controller
{
    public function update(Request $request, $id) {
        DB::transaction(function () use ($request, $id) {
            $attendance = Attendance::findOrFail($id);

            $attendance->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'note' =>$request->note,
                'status' => '承認済み',
            ]);

            BreakTime::where('attendance_id', $attendance->id)->delete();

            foreach ($request->breaks as $break) {
                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_number' => $break['break_number'],
                    'start_time' => $break['start_time'] ?? null,
                    'end_time' => $break['end_time'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.attendance.show', $id)->with('success', '勤怠修正を承認しました');
    }

    public function index() {
        $changeRequests = AttendanceChangeRequestModels::with(['attendance.user'])->where('status', '承認待ち')->orderBy('created_at', 'desc')->get();

        return view('admin.attendance.change.index', compact('changeRequests'));
    }

    public function show($attendance_correct_request_id) {
        $changeRequest = AttendanceChangeRequestModels::with([
            'user',
            'attendance.breakTimes',
            'breakChanges'
        ])->findOrFail($attendance_correct_request_id);

        return view('admin.attendance.change.show', compact('changeRequest'));
    }

    public function approve($attendance_correct_request_id) {
        $changeRequest = AttendanceChangeRequestModels::with([
            'attendance',
            'breakChanges'
        ])->findOrFail($attendance_correct_request_id);

        $attendance = $changeRequest->attendance;

        $attendance->update([
            'start_time' => $changeRequest->start_time,
            'end_time' => $changeRequest->end_time,
            'status' => '承認済み',
        ]);

        foreach ($changeRequest->breakChanges as $break) {
            $attendance->breakTimes()->create([
                'start_time' => $break->start_time,
                'end_time' => $break->end_time,
            ]);
        }

        $changeRequest->update([
            'status' => '承認済み',
        ]);

        return redirect()->route('admin.attendance.change.index')->with('success', '修正申請を承認しました');
    }
}
