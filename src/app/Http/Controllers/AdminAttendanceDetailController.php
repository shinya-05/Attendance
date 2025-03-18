<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function approve(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // 承認処理
        $attendance->update([
            'status' => '承認済み'
        ]);

        return redirect()->route('admin.attendance.detail', ['id' => $attendance->id])
            ->with('success', '勤怠修正を承認しました');
    }

    public function reject(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // 却下処理
        $attendance->update([
            'status' => '却下'
        ]);

        return redirect()->route('admin.attendance.detail', ['id' => $attendance->id])
            ->with('error', '勤怠修正を却下しました');
    }
}
