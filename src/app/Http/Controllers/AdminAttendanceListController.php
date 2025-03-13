<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminAttendanceListController extends Controller
{
    public function show($id, Request $request)
    {
        $user = User::findOrFail($id); // ユーザー情報を取得

        // クエリパラメータで指定された月、または現在の月
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $currentDate = Carbon::createFromFormat('Y-m', $month);
        $previousMonth = $currentDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentDate->copy()->addMonth()->format('Y-m');

        // 指定ユーザーの指定月の勤怠データを取得
        $attendances = Attendance::where('user_id', $id)
            ->whereYear('date', $currentDate->year)
            ->whereMonth('date', $currentDate->month)
            ->get()
            ->map(function ($attendance) {
                $attendance->rest_time = $this->calculateRestTime($attendance);
                $attendance->total_time = $this->calculateTotalTime($attendance);
                return $attendance;
            });

        return view('admin.attendance_user_list', [
            'user' => $user,
            'attendances' => $attendances,
            'currentYearMonth' => $currentDate->format('Y/m'),
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
        ]);
    }

    private function calculateRestTime($attendance)
    {
        $totalRestTime = $attendance->rests->sum(function ($rest) {
            return strtotime($rest->end_time) - strtotime($rest->start_time);
        });

        return gmdate('H:i', $totalRestTime);
    }

    private function calculateTotalTime($attendance)
    {
        $start = strtotime($attendance->start_time);
        $end = strtotime($attendance->end_time);
        $rest = strtotime($this->calculateRestTime($attendance));

        $totalWorkTime = ($end - $start) - $rest;

        return gmdate('H:i', $totalWorkTime);
    }
}
