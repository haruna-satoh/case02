<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceChangeRequest as AttendanceChangeRequestModel;
use App\Models\AttendanceBreakChangeRequest;
use App\Http\Requests\AttendanceChangeRequest;

class AttendanceChangeRequestController extends Controller
{
    public function store(AttendanceChangeRequest $request, $attendance_id) {
        $attendance = Attendance::where('id', $attendance_id)->where('user_id', auth()->id())->firstOrFail();

        $changeRequest = AttendanceChangeRequestModel::create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'note' => $request->note,
            'status' => '承認待ち',
        ]);

        if ($request->breaks) {
            foreach ($request->breaks as $breakData) {
                AttendanceBreakChangeRequest::create([
                    'attendance_change_request_id' => $changeRequest->id,
                    'break_number' => $breakData['break_number'],
                    'start_time' => $breakData['start_time'] ?? null,
                    'end_time' => $breakData['end_time'] ?? null,
                ]);
            }
        }

        $attendance->update([
            'status' => '承認待ち',
        ]);

        return redirect()->back()->with('success', '修正申請を送信しました！');
    }
}
