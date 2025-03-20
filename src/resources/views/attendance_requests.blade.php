@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_list.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance_requests.css') }}">
@endsection

@section('main')
<div class="container">
    <h1>申請一覧</h1>

<!-- タブメニュー -->
    <div class="tabs">
        <a href="{{ route('attendance.requests', ['tab' => 'pending']) }}" class="tab-link {{ request('tab', 'pending') == 'pending' ? 'active' : '' }}">承認待ち</a>
        <a href="{{ route('attendance.requests', ['tab' => 'approved']) }}" class="tab-link {{ request('tab') == 'approved' ? 'active' : '' }}">承認済み</a>
    </div>

    <!-- 承認待ち一覧 -->
    @if(request('tab', 'pending') == 'pending')
    <div id="pending" class="tab-content active">
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
                @php $pendingRequests = $requests->where('status', '承認待ち'); @endphp
                @if($pendingRequests->isNotEmpty())
                    @foreach ($pendingRequests as $request)
                        <tr>
                            <td>{{ $request->status }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->date }}</td>
                            <td>{{ $request->note }}</td>
                            <td>{{ $request->created_at->format('Y/m/d') }}</td>
                            <td><a href="{{ route('attendance.detail', ['id' => $request->id]) }}">詳細</a></td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="6">承認待ちの申請はありません。</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @endif

    <!-- 承認済み一覧 -->
    @if(request('tab') == 'approved')
    <div id="approved" class="tab-content active">
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
                @php $approvedRequests = $requests->where('status', '承認済み'); @endphp
                @if($approvedRequests->isNotEmpty())
                    @foreach ($approvedRequests as $request)
                        <tr>
                            <td>{{ $request->status }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->date }}</td>
                            <td>{{ $request->note }}</td>
                            <td>{{ $request->created_at->format('Y/m/d') }}</td>
                            <td><a href="{{ route('attendance.detail', ['id' => $request->id]) }}">詳細</a></td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="6">承認済みの申請はありません。</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection


