<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Time Cade</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__logo">
                <a href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ">
                </a>
            </div>

            @if(Auth::guard('admin')->check())
                <nav class="header__nav">
                    <ul class="header__list">
                        <li>
                            <a href="/admin/attendance/list">勤怠一覧</a>
                        </li>
                        <li>
                            <a href="/admin/staff/list">スタッフ一覧</a>
                        </li>
                        <li>
                            <a href="/stamp_correction_request/list">申請一覧</a>
                        </li>
                        <li>
                            <form action="/admin/logout" method="post">
                                @csrf
                                <button type="submit">ログアウト</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            @elseif(Auth::check())
                <nav class="header__nav">
                    <ul class="header__list">
                        <li>
                            <a href="/attendance">勤怠</a>
                        </li>
                        <li>
                            <a href="/attendance/list">勤怠一覧</a>
                        </li>
                        <li>
                            <a href="/stamp_correction_request/list">申請</a>
                        </li>
                        <li>
                            <form action="/logout" method="post">
                                @csrf
                                <button type="submit">ログアウト</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>