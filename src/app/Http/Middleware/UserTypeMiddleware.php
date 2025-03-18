<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRequest;

class UserTypeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            // 管理者の場合、管理者用の申請データを取得
            $requests = AttendanceRequest::with('user', 'attendance')->get();
            return response()->view('admin.attendance_requests', ['requests' => $requests]);
        }

        if (Auth::guard('web')->check()) {
            // 一般ユーザーの場合、該当ユーザーの申請データを取得
            $requests = AttendanceRequest::with('attendance')
                ->where('user_id', Auth::id())
                ->get();
            return response()->view('attendance_requests', ['requests' => $requests]);
        }

        // 未認証ユーザーはログインページへリダイレクト
        return redirect('/login');
    }
}
