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

    // CSV出力
    public function exportCsv(Request $request, $userId) {
        $user = User::findOrFail($userId);

        $month = $request->query('month') ? Carbon::parse($request->query('month') . '-01') : Carbon::now()->startOfMonth();

        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $attendances = Attendance::where('user_id', $user->id)->whereBetween('date', [$startOfMonth, $endOfMonth])->orderBy('date')->get();

        $fileName = 'attendance_' . $user->name . '_' . $month->format('Y_m') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($attendances) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                '日付',
                '出勤',
                '退勤',
                '休憩',
                '合計'
            ]);

            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    Carbon::parse($attendance->date)->format('Y/m/d'),
                    optional($attendance->start_time)->format('H:i'),
                    optional($attendance->end_time)->format('H:i'),
                    $attendance->formatted_break_time ?? '',
                    $attendance->formatted_total_time ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
