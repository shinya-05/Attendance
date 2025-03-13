<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        Carbon::setLocale('ja'); // 日本語ロケールを設定

        $currentDate = Carbon::now()->translatedFormat('Y年m月d日(D)');
        $currentTime = Carbon::now()->format('H:i');

        $attendance = Attendance::getAttendance();
        $status = '勤務外';
        $isOnBreak = false;

        if ($attendance) {
            if ($attendance->end_time) {
                $status = '退勤済';
            } elseif ($attendance->start_time) {
                $status = '出勤中';
                $isOnBreak = $attendance->rests()->whereNull('end_time')->exists();
                if ($isOnBreak) {
                    $status = '休憩中';
                } else {
                    $status = '出勤中';
                }
            }
        }

        return view('attendance', compact('currentDate', 'currentTime', 'status', 'isOnBreak'));
    }

    public function startAttendance()
    {
        $userId = Auth::id();
        $date = Carbon::now()->toDateString();
        $time = Carbon::now()->toTimeString();

        Attendance::create([
            'user_id' => $userId,
            'date' => $date,
            'start_time' => $time,
        ]);

        return redirect()->route('attendance.index');
    }

    public function endAttendance()
    {
        $userId = Auth::id();
        $date = Carbon::now()->toDateString();
        $time = Carbon::now()->toTimeString();

        Attendance::where('user_id', $userId)->where('date', $date)->update(['end_time' => $time]);

        return redirect()->route('attendance.index');
    }

    public function toggleBreak()
    {
        $attendance = Attendance::getAttendance();
        if (!$attendance) {
            return redirect()->route('attendance.index');
        }

        $time = Carbon::now()->toTimeString();
        $ongoingBreak = $attendance->rests()->whereNull('end_time')->first();

        if ($ongoingBreak) {
            $ongoingBreak->update(['end_time' => $time]);
        } else {
            Rest::create([
                'attendance_id' => $attendance->id,
                'start_time' => $time,
            ]);
        }

        return redirect()->route('attendance.index');
    }

}
