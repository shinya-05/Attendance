<header class="header">
    <div class="header-logo">
            <a href="/attendance"><img class="header__img" src="{{ asset('images/logo.svg') }}" alt="ロゴ画像"></a>
    </div>

    <nav class="header-nav">
        <ul>
            @if(Auth::guard('web')->check())
            <li><a href="/attendance" class="header-nav__list">勤怠</a></li>
            <li><a href="/attendance/list" class="header-nav__list">勤怠一覧</a></li>
            <li><a href="{{ route('attendance.requests') }}" class="header-nav__list">申請</a></li>
            <li><a href="/logout" class="header__logout">ログアウト</a></li>

            @elseif(Auth::guard('admin')->check())
            <li><a href="/admin/attendance/list" class="header-nav__list">勤怠一覧</a></li>
            <li><a href="/admin/staff/list" class="header-nav__list">スタッフ一覧</a></li>
            <li><a href="{{ route('attendance.requests') }}" class="header-nav__list">申請一覧</a></li>
            <li><a href="/logout" class="header__logout">ログアウト</a></li>
            @endif
        </ul>
    </nav>
</header>