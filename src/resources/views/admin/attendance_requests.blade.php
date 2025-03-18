@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_list.css') }}">

@section('main')
<h1>申請一覧</h1>
<table class="attendance-table">
    <thead>
        <tr>
            <th>状態</th>
            <th>名前</th>
            <th>対象日時</th>
            <th>申請理由</th>
            <th>申請日時</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $request)
            <tr>
                <td>承認待ち</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ $request->date }}</td>
                <td>{{ $request->note }}</td>
                <td>{{ $request->created_at->format('Y/m/d') }}</td>
                <td>詳細</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
