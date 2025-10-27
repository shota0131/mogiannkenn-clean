<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('css')
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="{{ route('items.index') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
            </a>
        </div>

        <div class="search-bar">
            <form method="GET" action="{{ route('items.index') }}">
                <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            </form>
        </div>

        <nav class="nav-links">
            @auth 
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-button">ログアウト</button>
                </form>

                <a href="{{ route('profile.index') }}">マイページ</a>
                <a href="{{ route('items.create') }}" class="sell-button">出品</a>
            @else
                <a href="{{ route('login') }}">ログイン</a>
                <a href="{{ route('register') }}">会員登録</a>
            @endauth
        </nav>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>