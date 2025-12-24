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

    public function update(Request $request, $id) {
        $attendance =Attendance::with('breakTimes')->findOrFail($id);

        $attendance->update([
            'start_time' =>$request->start_time,
            'end_time' => $request->end_time,
            'note' => $request->note,
            'status' => '承認済み',
        ]);

        if ($request->breaks) {
            foreach ($request->breaks as $index => $break) {
                if (empty($break['start_time']) && empty($break['end_time'])) {
                    continue;
                }

                $attendance->breakTimes()->updateOrCreate(
                    ['id' => $attendance->breakTimes[$index]->id ?? null],
                    [
                        'start_time' => $break['start_time'],
                        'end_time' => $break['end_time'],
                    ]
                );
            }
        }

        return redirect()->route('admin.attendance.show', $attendance->id)->with('success', '勤怠情報を修正しました');
    }
}