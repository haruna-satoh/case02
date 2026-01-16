<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceChangeRequest as AttendanceChangeRequestModel;
use App\Models\BreakTime;
use App\Http\Requests\AttendanceChangeRequest;
use App\Http\Requests\Admin\AttendanceUpdateRequest;

class AttendanceChangeController extends Controller
{
    public function update(Request $request, $id) {
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

        return redirect()->route('admin.attendance.show', $id)->with('success', '勤怠修正を承認しました');
    }

    public function index(Request $request) {
        $tab = $request->query('tab', 'pending');

        $query = AttendanceChangeRequestModel::with(['user', 'attendance'])->orderBy('created_at', 'desc');

        if ($tab === 'approved') {
            $query->where('status', '承認済み');
        } else {
            $query->where('status', '承認待ち');
        }

        $changeRequests = $query->get();

        return view('admin.attendance.change.index', compact('changeRequests', 'tab'));
    }

    public function show($attendance_correct_request_id) {
        $changeRequest = AttendanceChangeRequestModel::with([
            'user',
            'attendance.breakTimes',
            'breakChanges'
        ])->findOrFail($attendance_correct_request_id);

        return view('admin.attendance.change.show', compact('changeRequest'));
    }

    public function approve(AttendanceUpdateRequest $request, $id) {
        $changeRequest = AttendanceChangeRequestModel::with([
            'attendance',
            'breakChanges'
        ])->findOrFail($id);

        $attendance = $changeRequest->attendance;

        $attendance->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => '承認済み',
        ]);

        if($request->breaks) {
            foreach ($request->breaks as $index => $break) {
                if (empty($break['start_time']) && empty($break['end_time'])) continue;
                $attendance->breakTimes()->updateOrCreate(
                    ['id' => $attendance->breakTimes[$index]->id ?? null],
                    [
                        'start_time' => $break['start_time'],
                        'end_time' => $break['end_time'],
                    ]
                );
            }
        }

        $changeRequest->update([
            'status' => '承認済み',
        ]);

        return redirect()->route('admin.attendance.change.index')->with('success', '修正申請を承認しました');
    }
}
