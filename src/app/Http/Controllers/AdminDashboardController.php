<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;

class AdminDashboardController extends Controller
{

    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $currentDate = Carbon::createFromFormat('Y-m-d', $date);
        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $nextDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $attendances = Attendance::whereDate('date', $currentDate->toDateString())
            ->with('user')
            ->get()
            ->map(function ($attendance) {
                $attendance->rest_time = $this->calculateRestTime($attendance);
                $attendance->total_time = $this->calculateTotalTime($attendance);
                return $attendance;
            });

        return view('admin.attendance_list', [
            'attendances' => $attendances,
            'currentDate' => $currentDate->format('Y/m/d'),
            'currentDateFormatted' => $currentDate->format('Y年m月d日'),
            'previousDate' => $previousDate,
            'nextDate' => $nextDate,
        ]);
    }

    private function calculateRestTime($attendance)
    {
        $restTime = 0;
        foreach ($attendance->rests as $rest) {
            if ($rest->start_time && $rest->end_time) {
                $start = Carbon::parse($rest->start_time);
                $end = Carbon::parse($rest->end_time);
                $restTime += $end->diffInMinutes($start);
            }
        }
        return sprintf('%02d:%02d', floor($restTime / 60), $restTime % 60);
    }

    private function calculateTotalTime($attendance)
    {
        if ($attendance->start_time && $attendance->end_time) {
            $start = Carbon::parse($attendance->start_time);
            $end = Carbon::parse($attendance->end_time);
            $totalMinutes = $end->diffInMinutes($start) - ($attendance->rest_time_in_minutes ?? 0);
            return sprintf('%02d:%02d', floor($totalMinutes / 60), $totalMinutes % 60);
        }
        return '00:00';
    }
}
