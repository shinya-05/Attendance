@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_list.css') }}">
@endsection

@section('main')
<div class="container">
    <h1>{{ $user->name }}さんの勤怠</h1>

    <div class="navigation">
        <a href="{{ route('admin.attendance.list', ['id' => $user->id, 'month' => $previousMonth]) }}">&lt; 前月</a>
        <div class="current-month">
            <span class="calendar-icon"></span>
            <span>{{ $currentYearMonth }}</span>
        </div>
        <a href="{{ route('admin.attendance.list', ['id' => $user->id, 'month' => $nextMonth]) }}">翌月 &gt;</a>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>日付</th>
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
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->start_time }}</td>
                    <td>{{ $attendance->end_time }}</td>
                    <td>{{ $attendance->rest_time }}</td>
                    <td>{{ $attendance->total_time }}</td>
                    <td><a href="{{ route('admin.attendance.detail', ['id' => $attendance->id]) }}">詳細</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
