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

    public function approve($id)
{
    $request = AttendanceRequest::findOrFail($id);
    $attendance = $request->attendance;

    // 勤怠データを更新
    $attendance->update([
        'start_time' => $request->new_start_time,
        'end_time' => $request->new_end_time,
        'note' => $request->note,
        'status' => '承認済み'
    ]);

    // 申請を削除
    $request->delete();

    return redirect()->route('attendance.requests')->with('message', '申請を承認しました。');
    }


}
