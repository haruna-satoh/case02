<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function index() {
        $users = User::orderBy('id')->get();

        return view('admin.staff.index', compact('users'));
    }

    public function show(Request $request, $userId) {
        $user = User::findOrFail($userId);

        $month = $request->query('month') ? Carbon::createFromFormat('Y-m', $request->query('month')) : Carbon::now();

        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $attendances = Attendance::where('user_id', $user->id)->whereBetween('date', [$startOfMonth, $endOfMonth])->orderBy('date', 'desc')->get();

        $prevMonth = $month->copy()->subMonth()->format('Y-m');
        $nextMonth = $month->copy()->addMonth()->format('Y-m');

        return view('admin.staff.show', compact('user', 'attendances', 'month', 'prevMonth', 'nextMonth'));
    }
}
