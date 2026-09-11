<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>@yield('title', 'COACHTECHフリマ')</title>
    <link
        rel="stylesheet"
        href="{{ asset('css/common.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('css/auth.css') }}"
    >
</head>
<body>
    <header class="auth-header">
        <a
            class="auth-header__logo-link"
            href="{{ route('items.index') }}"
        >
            <img
                class="auth-header__logo"
                src="{{ asset('images/coachtech-logo.png') }}"
                alt="COACHTECH"
            >
        </a>
    </header>

    <main class="auth-main">
        @yield('content')
    </main>
</body>
</html>