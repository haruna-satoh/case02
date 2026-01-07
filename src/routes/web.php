<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\AttendanceChangeController as AdminAttendanceChangeController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceChangeRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/attendance/{id}', [AdminAttendanceController::class, 'show'])->name('admin.attendance.show');
    Route::patch('/attendance/{id}', [AdminAttendanceChangeController::class, 'update'])->name('admin.attendance.update');
    Route::get('/stamp_correction_request/list', [AdminAttendanceChangeController::class, 'index'])->name('admin.attendance.change.index');
    Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminAttendanceChangeController::class, 'show'])->name('admin.change_request.show');
    Route::patch('/stamp_correction_request/{id}/approve', [AdminAttendanceChangeController::class, 'approve'])->name('admin.change_request.approve');
    Route::get('/staff/list', [StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('/attendance/staff/{id}', [StaffController::class, 'show'])->name('admin.staff.show');
    Route::get('/attendance/staff/{id}/csv', [StaffController::class, 'exportCsv'])->name('admin.staff.csv');
});

Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/start', [AttendanceController::class, 'start'])->name('attendance.start');
    Route::post('/attendance/end', [AttendanceController::class, 'end'])->name('attendance.end');
    Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart'])->name('attendance.break.start');
    Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd'])->name('attendance.break.end');
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
    Route::get('attendance/detail/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/change/{id}', [AttendanceChangeRequestController::class, 'store'])->name('attendance.change.store.user');
    Route::get('/stamp_correction_request/list', [AttendanceChangeRequestController::class, 'index'])->name('attendance.change.index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/admin/login', [AdminAuthController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::post('/admin/logout', function () {
    Auth::guard('admin')->logout();
    return redirect('/admin/login');
})->name('admin.logout');

Route::get('/email/verify', function() {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect('/attendance');
        }

        return view('auth.verify');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/attendance');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送しました');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.resend');