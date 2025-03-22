@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_detail.css') }}">
@endsection

@section('main')
<div class="container">
    <h1>勤怠詳細</h1>
    @if ($errors->any())
    <div class="alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('admin.attendance.update', ['id' => $attendance->id]) }}" method="POST">
        @csrf
        @method('PUT')
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
                        <input type="text" name="start_time" value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}">
                        <p>〜</p>
                        <input type="text" name="end_time" value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}">
                    </div>
                </td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    @foreach ($attendance->rests as $rest)
                        <div class="detail-table__input">
                            <input type="text" name="rest_start[]" value="{{ \Carbon\Carbon::parse($rest->start_time)->format('H:i') }}" 
                                {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>
                            <p>〜</p>
                            <input type="text" name="rest_end[]" value="{{ \Carbon\Carbon::parse($rest->end_time)->format('H:i') }}" 
                                {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>
                        </div>
                        @endforeach
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td>
                    <textarea name="note" >{{ $attendance->note }}</textarea>
                </td>
            </tr>
        </table>
        <div class="btn-container">
            <button type="submit" class="btn">修正</button>
        </div>
    </form>
</div>
@endsection

