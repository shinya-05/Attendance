@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_detail.css') }}">
@endsection

@section('main')
<div class="container">
    <h1>勤怠詳細</h1>
    <table class="detail-table">
        <tr>
            <th>名前</th>
            <td>{{ $attendance->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>
                <div class="detail-table__day">
                    <p>{{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}</p>
                    <p>{{ \Carbon\Carbon::parse($attendance->date)->format('n月 j日') }}</p>
                </div>
            </td>
        </tr>
        <tr>
            <th>出勤・退勤</th>
            <td>
                <div class="detail-table__input">
                    <input type="text" value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}" disabled>
                    <p>〜</p>
                    <input type="text" value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}" disabled>
                </div>
            </td>
        </tr>
        <tr>
            <th>休憩</th>
            <td>
                <div class="detail-table__input">
                    <input type="text" value="{{ \Carbon\Carbon::parse($attendance->rest_start)->format('H:i') }}" disabled>
                    <p>〜</p>
                    <input type="text" value="{{ \Carbon\Carbon::parse($attendance->rest_end)->format('H:i') }}" disabled>
                </div>
            </td>
        </tr>
        <tr>
            <th>備考</th>
            <td>
                <p>{{ $attendance->note }}</p>
            </td>
        </tr>
    </table>

    <div class="btn-container">
        @if($attendance->status == '承認待ち')
            <form action="{{ route('attendance.approve', ['attendance' => $attendance->id]) }}" method="POST">
                @csrf
                <button type="submit" class="btn">承認</button>
            </form>
        @else
            <button class="btn disabled" disabled>承認済み</button>
        @endif
    </div>
</div>
@endsection
