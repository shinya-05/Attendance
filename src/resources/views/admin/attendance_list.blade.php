@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_list.css') }}">
@endsection

@section('main')
<div class="container">
    <h1>{{ $currentDateFormatted }}の勤怠</h1>

    <div class="navigation">
        <a href="{{ route('admin.attendance', ['date' => $previousDate]) }}">&lt; 前日</a>
        <div class="current-month">
            <span class="calendar-icon"></span>
            <span>{{ $currentDate }}</span>
        </div>
        <a href="{{ route('admin.attendance', ['date' => $nextDate]) }}">翌日 &gt;</a>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->start_time }}</td>
                    <td>{{ $attendance->end_time }}</td>
                    <td>{{ $attendance->rest_time }}</td>
                    <td>{{ $attendance->total_time }}</td>
                    <td>
                        <a href="{{ Auth::user()->is_admin 
                            ? route('admin.attendance.detail', ['id' => $attendance->id]) 
                            : route('attendance.detail', ['id' => $attendance->id]) }}">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
