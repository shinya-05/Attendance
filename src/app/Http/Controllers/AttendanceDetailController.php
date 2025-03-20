<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceDetailController extends Controller
{
    public function show($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);

        return view('attendance_detail', [
            'attendance' => $attendance,
        ]);
    }

    public function update(UpdateAttendanceRequest $request, $id)
    {
        $attendance = Attendance::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    // 勤怠データを修正申請状態に変更
        $attendance->update([
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'rest_start' => $request->rest_start,
        'rest_end' => $request->rest_end,
        'note' => $request->note,
        'status' => '承認待ち' // 修正申請状態へ変更
    ]);

        return redirect()->route('attendance.detail', ['id' => $attendance->id]);
    }
}

