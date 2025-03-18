<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AdminAttendanceController extends Controller
{
    public function approve($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update(['status' => '承認済み']);

        return redirect()->route('admin.attendance.requests')->with('message', '申請を承認しました。');
    }

}
