@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('main')
<div class="main">
    <div class="status">{{ $status }}</div>
    <div class="date">{{ $currentDate }}</div>
    <div class="time">{{ $currentTime }}</div>

    <div class="attendance-button">
        @if ($status === '勤務外')
        <form action="{{ route('attendance.start') }}" method="POST">
            @csrf
            <button class="work__button">出勤</button>
        </form>
        @elseif ($status === '出勤中')
        <form action="{{ route('attendance.end') }}" method="POST">
            @csrf
            <button class="end__button">退勤</button>
        </form>
        <form action="{{ route('attendance.break') }}" method="POST">
            @csrf
            <button class="rest-start__button">休憩入</button>
        </form>
        @elseif ($status === '休憩中')
        <form action="{{ route('attendance.break') }}" method="POST">
            @csrf
            <button class="rest-end__button">休憩戻</button>
        </form>
        @elseif ($status === '退勤済')
        <p>お疲れ様でした。</p>
        @endif
    </div>
</div>
@endsection
