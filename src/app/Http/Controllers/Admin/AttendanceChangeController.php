<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceChangeRequest as AttendanceChangeRequestModels;
use App\Models\AttendanceBreak;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AttendanceChangeRequest;

class AttendanceChangeController extends Controller
{
    public function update($id) {
        DB::transaction(function () use ($id) {
            $request = AttendanceChangeRequestModels::with('breakRequests')->findOrFail($id);
            $attendance = $request->attendance;

            $attendance->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => '承認済み',
            ]);

            AttendanceBreak::where('attendance_id', $attendance->id)->delete();

            foreach ($request->breakRequests as $break) {
                AttendanceBreak::create([
                    'attendance_id' => $attendance->id,
                    'break_number' => $break->break_number,
                    'start_time' => $break->start_time,
                    'end_time' => $break->end_time,
                ]);
            }

            $request->update([
                'status' => '承認済み',
            ]);
        });

        return redirect()->back()->with('success', '勤怠修正を承認しました');
    }
}
