<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceRequestController extends Controller
{
    public function index()
{
    if (Auth::guard('admin')->check()) {
        // 管理者 → 全ての承認待ち
        $requests = Attendance::with('user')->where('status', '承認待ち')->get();
        return view('admin.attendance_requests', compact('requests'));
    }

    if (Auth::guard('web')->check()) {
        $pendingRequests = Attendance::with('user')
            ->where('user_id', Auth::id())
            ->where('status', '承認待ち')
            ->get();

        $approvedRequests = Attendance::with('user')
            ->where('user_id', Auth::id())
            ->where('status', '承認済み')
            ->get();

    return view('attendance_requests', compact('pendingRequests', 'approvedRequests'));
}


    return redirect()->route('login');
}


    public function showApproval($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);
        return view('admin.approve_attendance', compact('attendance'));
    }

    /**
     * 承認処理
     */
    public function approve($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->status = '承認済み';
        $attendance->save();

        return redirect()->route('attendance.requests');
    }

}
