<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceDetailController extends Controller
{
    public function show($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);

        return view('attendance_detail', [
            'attendance' => $attendance,
        ]);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('attendance.detail', ['id' => $attendance->id]);
    }
}

