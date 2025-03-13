<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;

class AttendanceListController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $currentDate = Carbon::createFromFormat('Y-m', $month);
        $previousMonth = $currentDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentDate->copy()->addMonth()->format('Y-m');

        $attendances = Attendance::whereYear('date', $currentDate->year)
            ->whereMonth('date', $currentDate->month)
            ->get()
            ->map(function ($attendance) {
                $attendance->rest_time = $this->calculateRestTime($attendance);
                $attendance->total_time = $this->calculateTotalTime($attendance);
                return $attendance;
            });

        return view('attendance_list', [
            'attendances' => $attendances,
            'currentYearMonth' => $currentDate->format('Y/m'),
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
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

