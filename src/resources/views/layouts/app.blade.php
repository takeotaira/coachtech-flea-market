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
    @yield('css')
</head>
<body>
    <header class="header">
        <a
            class="header__logo-link"
            href="{{ route('items.index') }}"
        >
            <img
                class="header__logo"
                src="{{ asset('images/coachtech-logo.png') }}"
                alt="COACHTECH"
            >
        </a>

        <form
            class="header__search"
            action="{{ route('items.index') }}"
            method="GET"
        >
            @if (request('tab') === 'mylist')
                <input
                    type="hidden"
                    name="tab"
                    value="mylist"
                >
            @endif

            <input
                class="header__search-input"
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="なにをお探しですか？"
            >
        </form>

        <nav class="header__nav">
            @auth
                <form
                    class="header__logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf
                    <button
                        class="header__nav-button"
                        type="submit"
                    >
                        ログアウト
                    </button>
                </form>
            @else
                <a
                    class="header__nav-link"
                    href="{{ route('login') }}"
                >
                    ログイン
                </a>
            @endauth

            <a
                class="header__nav-link"
                href="{{ route('mypage') }}"
            >
                マイページ
            </a>

            <a
                class="header__sell-link"
                href="{{ route('items.create') }}"
            >
                出品
            </a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>