<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index(Request $request) {
        $date = $request->get('date');

        if (!$date) {
            $date = Attendance::max('date');
        }

        if ($request->nav) {
            $date = $request->nav;
        }else {
            $date = $request->selected_date ?? Attendance::max('date');
        }

        $attendances = Attendance::where('date', $date)
            ->with(['user', 'breakTimes'])
            ->get();

        return view('admin.index', compact('attendances', 'date'));
    }

    public function show($id) {
        $attendance = Attendance::findOrFail($id);
        $user = $attendance->user;

        return view('admin.attendance.show', compact('attendance', 'user'));
    }

    public function update (Request $request, $id) {
        $attendance = Attendance::find($id);

        $attendance->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
            'note' => $request->note,
            'status' => '承認待ち',
        ]);

        return redirect('/admin/attendance/' . $id)->with('success', '修正申請を送信しました');
    }
}