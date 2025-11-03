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
            <h1 class="header__logo">
                <a href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ">
                </a>
            </h1>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>