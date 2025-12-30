<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class StaffController extends Controller
{
    // スタッフ一覧
    public function index() {
        $users = User::orderBy('id')->get();

        return view('admin.staff.index', compact('users'));
    }

    // スタッフ別勤怠一覧
    public function show(Request $request, $userId) {
        $user = User::findOrFail($userId);

        $month = $request->query('month') ? Carbon::parse($request->query('month') . '-01') : Carbon::now()->startOfMonth();

        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $attendances = Attendance::where('user_id', $user->id)->whereBetween('date', [$startOfMonth, $endOfMonth])->get()->keyBy(function ($attendance) {
            return Carbon::parse($attendance->date)->format('Y-m-d');
        });

        $dates = [];
        $current = $startOfMonth->copy();
        while ($current->lte($endOfMonth)) {
            $dates[] = $current->copy();
            $current->addDay();
        }

        $baseMonth = $month->copy();

        $prevMonth = $baseMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $baseMonth->copy()->addMonth()->format('Y-m');

        return view('admin.staff.show', compact('user', 'dates', 'attendances', 'month', 'prevMonth', 'nextMonth'));
    }
}
