<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index() {
        $attendances = Attendance::with('breakTimes', 'user')->get();

        return view('admin.index', compact('attendances'));
    }
}
