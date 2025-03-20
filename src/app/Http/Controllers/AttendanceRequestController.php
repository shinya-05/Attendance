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
        // 管理者がアクセスしている場合 → すべての承認待ち申請を取得
        $requests = Attendance::with('user')->where('status', '承認待ち')->get();
        return view('admin.attendance_requests', compact('requests'));
    } elseif (Auth::guard('web')->check()) {
        // 一般ユーザーがアクセスしている場合 → 自分の申請のみ取得
        $requests = Attendance::where('user_id', Auth::id())->get();
        return view('attendance_requests', compact('requests'));
    } else {
        // 未ログインの場合はログイン画面へリダイレクト
        return redirect()->route('login');
    }
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
