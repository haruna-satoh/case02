<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;

class StaffController extends Controller
{
    public function index() {
        $users = User::orderBy('id')->get();

        return view('admin.staff.index', compact('users'));
    }

    public function show($userId) {
        $user = User::findOrFail($userId);

        $attendances = Attendance::where('user_id', $user->id)->orderBy('date', 'desc')->get();

        return view('admin.staff.show', compact('user', 'attendances'));
    }
}
