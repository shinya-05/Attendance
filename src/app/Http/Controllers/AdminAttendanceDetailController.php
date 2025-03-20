<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;

class AdminAttendanceDetailController extends Controller
{
    public function show($id)
    {
        // すべての勤怠情報を取得（管理者用）
        $attendance = Attendance::with('user')->findOrFail($id);

        return view('admin.attendance_detail', [
            'attendance' => $attendance
        ]);
    }

    public function update(UpdateAttendanceRequest $request, $id)
    {
        // 勤怠データを取得
        $attendance = Attendance::findOrFail($id);

        // 更新
        $attendance->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'rest_start' => $request->rest_start,
            'rest_end' => $request->rest_end,
            'note' => $request->note,
            'status' => '承認済み', // 直接反映されるため、承認済みに変更
        ]);

        return redirect()->route('admin.attendance.detail', ['id' => $id]);
    }

}
