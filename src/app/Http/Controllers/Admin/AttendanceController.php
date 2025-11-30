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
}
