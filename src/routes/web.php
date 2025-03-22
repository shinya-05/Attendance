<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\AttendanceRequestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminAttendanceListController;
use App\Http\Controllers\AdminAttendanceDetailController;

use Illuminate\Support\Facades\Route;

//メール認証
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/attendance');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


//一般ユーザー
Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/start', [AttendanceController::class, 'startAttendance'])->name('attendance.start');
    Route::post('/attendance/end', [AttendanceController::class, 'endAttendance'])->name('attendance.end');
    Route::post('/attendance/break', [AttendanceController::class, 'toggleBreak'])->name('attendance.break');
    Route::get('/attendance/list', [AttendanceListController::class, 'index'])->name('attendance.list');
    Route::get('/attendance/{id}', [AttendanceDetailController::class, 'show'])->name('attendance.detail');
    Route::put('/attendance/{id}', [AttendanceDetailController::class, 'update'])->name('attendance.update');
    Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


});

//ログイン機能
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);
});


//管理者ユーザー
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
    Route::get('/admin/attendance/list', [AdminDashboardController::class, 'index'])->name('admin.attendance');
    Route::get('/admin/attendance/{id}', [AdminAttendanceDetailController::class, 'show'])
        ->name('admin.attendance.detail');
    Route::put('/admin/attendance/{id}', [AdminAttendanceDetailController::class, 'update'])
        ->name('admin.attendance.update');
    Route::get('/admin/staff/list', [AdminStaffController::class, 'index'])->name('admin.staff.list');
    Route::get('/admin/attendance/staff/{id}', [AdminAttendanceListController::class, 'show'])->name('admin.attendance.list');
    Route::get('/admin/attendance/{id}/export', [AdminAttendanceListController::class, 'export'])->name('admin.attendance.export');

    Route::get('/stamp_correction_request/approve/{attendance}', [AttendanceRequestController::class, 'showApproval'])
    ->name('attendance.approveForm');

    Route::post('/stamp_correction_request/approve/{attendance}', [AttendanceRequestController::class, 'approve'])
    ->name('attendance.approve');
});




Route::middleware(['user.type'])->group(function () {
    Route::get('/stamp_correction_request/list', [AttendanceRequestController::class, 'index'])
        ->name('attendance.requests');
});
